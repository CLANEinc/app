<?php
namespace Customize\Service;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Common\EccubeConfig;
use Eccube\Entity\CartItem;
use Eccube\Repository\CartRepository;
use Eccube\Repository\OrderRepository;
use Eccube\Repository\ProductClassRepository;
use Eccube\Service\Cart\CartItemAllocator;
use Eccube\Service\Cart\CartItemComparator;
use Eccube\Service\CartService;
use Eccube\Service\TaxRuleService;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class CategoryOptionCartService extends CartService
{
    protected $taxRuleService;

    /**
     * @var array
     */
    protected $eccubeConfig;

    public function __construct(
        SessionInterface $session,
        EntityManagerInterface $entityManager,
        ProductClassRepository $productClassRepository,
        CartRepository $cartRepository,
        CartItemComparator $cartItemComparator,
        CartItemAllocator $cartItemAllocator,
        OrderRepository $orderRepository,
        TokenStorageInterface $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        TaxRuleService $taxRuleService,
        EccubeConfig $eccubeConfig
    ) {
        $this->session = $session;
        $this->entityManager = $entityManager;
        $this->productClassRepository = $productClassRepository;
        $this->cartRepository = $cartRepository;
        $this->cartItemComparator = $cartItemComparator;
        $this->cartItemAllocator = $cartItemAllocator;
        $this->orderRepository = $orderRepository;
        $this->tokenStorage = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->taxRuleService = $taxRuleService;
        $this->eccubeConfig = $eccubeConfig;
    }

    public function addProductWithOption($productClassId, $Options, $quantity = 1)
    {
        $ProductClass = $this->productClassRepository->find($productClassId);
        if (!$ProductClass) {
            return false;
        }

        $ClassCategory1 = $ProductClass->getClassCategory1();
        if ($ClassCategory1 && !$ClassCategory1->isVisible()) {
            return false;
        }
        $ClassCategory2 = $ProductClass->getClassCategory2();
        if ($ClassCategory2 && !$ClassCategory2->isVisible()) {
            return false;
        }

        $exist_flg = false;
        $newItem = new CartItem();
        $newItem->setQuantity($quantity);
        $newItem->setProductClass($ProductClass);

        // DB????????????????????????????????????
        $optionJpArray = [];
        // ??????????????????????????????
        $total_option_price = 0;
        foreach ($Options as $optionKey => $OptionItem) {
            if (
                $OptionItem['type'] === 'number' ||
                $optionKey === '?????????'
            ) {
                $optionJpArray[$optionKey] = [
                    'value' => $OptionItem['value'],
                    'price' => 0
                ];
            } else {
                $option_price = isset($OptionItem['price']) ? $OptionItem['price'] : 0;
                if (
                    $optionKey === '?????????????????????' &&
                    $OptionItem['label'] === '??????' &&
                    $Options['?????????']['label'] === '?????????'
                ) {
                    // ????????????????????????????????????????????????2????????????
                    $option_price *= 2;
                }
                $optionJpArray[$optionKey] = [
                    'value' => $OptionItem['label'],
                    'price' => $option_price
                ];
                $total_option_price += $option_price;
            }
        }
        $newItem->setOptionSerial(serialize($optionJpArray));

        $allCartItems = [];
        foreach ($this->getCarts() as $Cart) {
            $CartItems = $Cart->getCartItems();
            foreach ($CartItems as $CartItem) {
                if ($CartItem->getOptionSerial() == null) {
                    $CartItems->removeElement($CartItem);
                }
            }
            $allCartItems = array_merge($allCartItems, $CartItems->toArray());
            foreach ($CartItems as $cartItem) {
                if ($this->cartItemComparator->compare($newItem, $cartItem)) {
                    $cartItem->setQuantity($cartItem->getQuantity() + $quantity);
                    $exist_flg = true;
                    break;
                }
            }
        }
        if ($exist_flg) {
            $this->restoreCarts($allCartItems);
            return;
        }

        $allCartItems = $this->mergeAllCartItems([$newItem]);

        // ?????????????????????????????????
        $price = 0;
        $priceList = $this->eccubeConfig['price_list'];

        foreach ($ProductClass->getProduct()->getProductCategories() as $productCategory) {
            $categoryName = $productCategory->getCategory()->getName();
            if (!isset($priceList[$categoryName])) {
                continue;
            }
            $priceArray = [];
            if (strpos($categoryName, '????????????') !== false) {
                if ($ProductClass->getProduct()->isSE()) {
                    $priceArray = $priceList[$categoryName . 'SE'][$optionJpArray['??????????????????']['value']];
                } else {
                    $priceArray = $priceList[$categoryName][$optionJpArray['??????????????????']['value']];
                }
            } elseif (strpos($categoryName, '????????????') !== false) {
                $categoryNameWithSE = $categoryName;
                if ($ProductClass->getProduct()->isSE()) {
                    $categoryNameWithSE .= 'SE';
                }
                if ($ProductClass->getProduct()->isSE001()) {
                    $categoryNameWithSE .= 'SE001';
                }
                if (
                    $optionJpArray['????????????????????????']['value'] === '?????????????????????' &&
                    $ProductClass->getProduct()->isSE() === false &&
                    substr($optionJpArray['?????????']['value'], 0, 1) === 'J' &&
                    substr($optionJpArray['?????????']['value'], -1) === 'H'
                ) {
                    $priceArray = $priceList[$categoryNameWithSE][$optionJpArray['????????????????????????']['value']][$optionJpArray['??????????????????']['value']][$optionJpArray['??????']['value'] . 'JH'];
                } else {
                    $priceArray = $priceList[$categoryNameWithSE][$optionJpArray['????????????????????????']['value']][$optionJpArray['??????????????????']['value']][$optionJpArray['??????']['value']];
                }
            }
            foreach ($priceArray as $sizeAndPrice) {
                if (
                    $ProductClass->getProduct()->isCurtain() &&
                    $optionJpArray['???']['value'] <= $sizeAndPrice['width_max'] &&
                    $optionJpArray['??????']['value'] <= $sizeAndPrice['height_max']
                ) {
                    $price = $sizeAndPrice['price'];
                    break;
                }
                if (
                    $ProductClass->getProduct()->isShade() &&
                    $optionJpArray['???']['value'] <= $sizeAndPrice['width_max']
                ) {
                    $price = $sizeAndPrice['price'];
                    break;
                }
            }
        }

        $price += $total_option_price;
        $newItem->setPrice($price + $this->taxRuleService->getTax($price, $ProductClass->getProduct(), $ProductClass));
        $newItem->setPriceWithoutTax($price);
        $this->restoreCarts($allCartItems);

        return true;
    }

    public function removeCartItem($CartItem)
    {
        $allCartItems = $this->mergeAllCartItems();
        $foundIndex = -1;
        foreach ($allCartItems as $index => $itemInCart) {
            if ($this->cartItemComparator->compare($itemInCart, $CartItem)) {
                $foundIndex = $index;
                break;
            }
        }

        array_splice($allCartItems, $foundIndex, 1);
        $this->restoreCarts($allCartItems);
    }
}
