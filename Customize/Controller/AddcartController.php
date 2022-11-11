<?php
namespace Customize\Controller;

use Eccube\Common\EccubeConfig;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Product;
use Eccube\Entity\Master\ProductStatus;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\AddCartType;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Customize\Service\CategoryOptionCartService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AddcartController extends AbstractController
{
    protected $purchaseFlow;
    protected $cartService;

    /**
     * @var array
     */
    protected $eccubeConfig;

    public function __construct(
        PurchaseFlow $cartPurchaseFlow,
        CategoryOptionCartService $cartService,
        EccubeConfig $eccubeConfig
    ) {
        $this->purchaseFlow = $cartPurchaseFlow;
        $this->cartService = $cartService;
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * カートに追加.
     *
     * @Route("/products/add_cart/{id}", name="product_add_cart", methods={"POST"}, requirements={"id" = "\d+"})
     */
    public function addCart(Request $request, Product $Product)
    {
        // エラーメッセージの配列
        $errorMessages = [];
        if (!$this->checkVisibility($Product)) {
            throw new NotFoundHttpException();
        }

        $builder = $this->formFactory->createNamedBuilder(
            '',
            AddCartType::class,
            null,
            [
                'product' => $Product,
                'id_add_product_id' => false,
            ]
        );

        $event = new EventArgs(
            [
                'builder' => $builder,
                'Product' => $Product,
            ],
            $request
        );
        $this->eventDispatcher->dispatch(EccubeEvents::FRONT_PRODUCT_CART_ADD_INITIALIZE, $event);

        /* @var $form \Symfony\Component\Form\FormInterface */
        $form = $builder->getForm();
        $form->handleRequest($request);

        if (!$form->isValid()) {
            foreach($form->all() as $child){
                $config = $child->getConfig();
                foreach($child->getErrors() as $error){
                    $errorMessages[] = $config->getOption('label') .':'. $error->getMessage();
                }
            }
        }

        $addCartData = $form->getData();
        if(count($errorMessages) == 0){
            log_info(
                'カート追加処理開始',
                [
                    'product_id' => $Product->getId(),
                    'product_class_id' => $addCartData['product_class_id'],
                    'quantity' => $addCartData['quantity'],
                ]
            );
            // カートへ追加
            if ($Product->isCurtain()) {
                $CategoryOptions = $this->eccubeConfig['option_list']['カーテン'];
            } else {
                $CategoryOptions = $this->eccubeConfig['option_list']['シェード'];
            }

            $KeyAndChoicePair = [];
            foreach ($CategoryOptions as $optionKey => $Option) {
                if ($Product->isSE001() && $optionKey === 'lining') {
                    continue;
                }
                $choice = $form->get($optionKey)->getData();
                $KeyAndChoicePair[$optionKey] = $choice;
            }
            // 選択できないオプションの組み合わせを修正
            if (isset($KeyAndChoicePair['shadetype'])) {
                if ($KeyAndChoicePair['shadetype'] == 'single') {
                    // シングルシェードは裏生地なし
                    $KeyAndChoicePair['lining'] = null;
                }
                if ($KeyAndChoicePair['shadetype'] == 'double') {
                    // ダブルシェードは縫製スタイル：プレーンのみ
                    $KeyAndChoicePair['houseistyle'] = 'plain';
                }
            }

            // カーテンは仕上がり幅を算出
            // 算出した仕上がり幅で「幅」を上書きし、ユーザー入力の「幅」はユーザー入力幅として保存。
            if ($Product->isCurtain()) {
                $inputWidth = $KeyAndChoicePair['width'];
                $rate = 1.03;
                if ($KeyAndChoicePair['houseigrade'] === 'flat') {
                    // 縫製グレード：フラットは仕上がり幅1.2倍
                    $rate = 1.2;
                }
                $finishWidth = round($inputWidth * $rate);
                if (
                    $KeyAndChoicePair['handing'] === 'double' &&
                    $finishWidth % 2 !== 0
                ) {
                    // 両開きかつ仕上がり幅が奇数の場合、偶数cmに切り上げる
                    $finishWidth += 1;
                }
                $KeyAndChoicePair['inputWidth'] = $inputWidth;
                $KeyAndChoicePair['width'] = $finishWidth;
            }

            $Options = [];
//            ＄Optionsはこの形で cartService に渡す。（例）
//            [
//                '縫製グレード' => [
//                    'value' => 'deluxe',
//                    'type' => 'radio',
//                    'label' => 'デラックス',
//                    'default' => true
//                ], ...
//            ]
            foreach ($KeyAndChoicePair as $optionKey => $choice) {
                if ($optionKey === 'inputWidth') {
                    $Options['入力幅'] = [
                        'type' => 'number',
                        'value' => $KeyAndChoicePair['inputWidth']
                    ];
                    continue;
                }
                if (
                    isset($KeyAndChoicePair['shadetype']) &&
                    $KeyAndChoicePair['shadetype'] === 'single' &&
                    $optionKey === 'lining'
                ) {
                    // シェードタイプ：シングルの場合、裏生地オプションは無し
                    continue;
                }
                $selectedItem = [];
                if ($CategoryOptions[$optionKey]['type'] === 'radio') {
                    $selectedItem = $CategoryOptions[$optionKey]['items'][$choice];
                }
                $selectedItem['value'] = $choice;   // deluxe とか
                $selectedItem['type'] = $CategoryOptions[$optionKey]['type']; // radio or number
                $Options[$CategoryOptions[$optionKey]['label']] = $selectedItem;
            }

            $this->cartService->addProductWithOption($addCartData['product_class_id'], $Options, $addCartData['quantity']);

            // 明細の正規化
            $Carts = $this->cartService->getCarts();
            foreach ($Carts as $Cart) {
                $result = $this->purchaseFlow->validate($Cart, new PurchaseContext($Cart, $this->getUser()));
                // 復旧不可のエラーが発生した場合は追加した明細を削除.
                if ($result->hasError()) {
                    $this->cartService->removeProduct($addCartData['product_class_id']);
                    foreach ($result->getErrors() as $error) {
                        $errorMessages[] = $error->getMessage();
                    }
                }
                foreach ($result->getWarning() as $warning) {
                    $errorMessages[] = $warning->getMessage();
                }
            }
            $this->cartService->save();

            log_info(
                'カート追加処理完了',
                [
                    'product_id' => $Product->getId(),
                    'product_class_id' => $addCartData['product_class_id'],
                    'quantity' => $addCartData['quantity'],
                ]
            );

            $event = new EventArgs(
                [
                    'form' => $form,
                    'Product' => $Product,
                ],
                $request
            );
            $this->eventDispatcher->dispatch(EccubeEvents::FRONT_PRODUCT_CART_ADD_COMPLETE, $event);
        }

        if ($event->getResponse() !== null) {
            return $event->getResponse();
        }

        if ($request->isXmlHttpRequest()) {
            // ajaxでのリクエストの場合は結果をjson形式で返す。

            // 初期化
            $done = null;
            $messages = [];

            if (empty($errorMessages)) {
                // エラーが発生していない場合
                $done = true;
                array_push($messages, trans('front.product.add_cart_complete'));
            } else {
                // エラーが発生している場合
                $done = false;
                $messages = $errorMessages;
            }

            return $this->json(['done' => $done, 'messages' => $messages]);
        } else {
            // ajax以外でのリクエストの場合はカート画面へリダイレクト
            if (empty($errorMessages)) {
                return $this->redirectToRoute('cart');
            }else{
                foreach ($errorMessages as $errorMessage) {
                    log_info("====error: $errorMessage");
                    $this->addRequestError($errorMessage);
                }
                return $this->redirect($request->headers->get('referer'));
            }
        }
    }

    private function checkVisibility(Product $Product)
    {
        $is_admin = $this->session->has('_security_admin');

        // 管理ユーザの場合はステータスやオプションにかかわらず閲覧可能.
        if (!$is_admin) {
            // 在庫なし商品の非表示オプションが有効な場合.
            // if ($this->BaseInfo->isOptionNostockHidden()) {
            //     if (!$Product->getStockFind()) {
            //         return false;
            //     }
            // }
            // 公開ステータスでない商品は表示しない.
            if ($Product->getStatus()->getId() !== ProductStatus::DISPLAY_SHOW) {
                return false;
            }
        }

        return true;
    }
}
