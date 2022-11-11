<?php
/*
* Plugin Name : ProductOption
*
* Copyright (C) BraTech Co., Ltd. All Rights Reserved.
* http://www.bratech.co.jp/
*
* For the full copyright and license information, please view the LICENSE
* file that was distributed with this source code.
*/

namespace Customize\Controller;

use Eccube\Controller\CartController;
use Eccube\Entity\CartItem;
use Eccube\Repository\CartItemRepository;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\ProductClassRepository;
use Eccube\Service\PurchaseFlow\PurchaseFlow;
use Customize\Service\CategoryOptionCartService;
use Symfony\Component\Routing\Annotation\Route;

class CartOperationController extends CartController
{
    protected $cartItemRepository;

    public function __construct(
        CartItemRepository $cartItemRepository,
        ProductClassRepository $productClassRepository,
        CategoryOptionCartService $categoryOptionCartService,
        PurchaseFlow $cartPurchaseFlow,
        BaseInfoRepository $baseInfoRepository
    ) {
        $this->cartItemRepository = $cartItemRepository;
        $this->productClassRepository = $productClassRepository;
        $this->cartService = $categoryOptionCartService;
        $this->purchaseFlow = $cartPurchaseFlow;
        $this->baseInfo = $baseInfoRepository->get();
    }

    /**
     * カート明細の加算/減算/削除を行う.
     *
     * - 加算
     *      - 明細の個数を1増やす
     * - 減算
     *      - 明細の個数を1減らす
     *      - 個数が0になる場合は、明細を削除する
     * - 削除
     *      - 明細を削除する
     *
     * @Route(
     *     path="/categoryoption_cart/{operation}/{cartItemId}",
     *     name="categoryoption_cart_handle_item",
     *     methods={"PUT"},
     *     requirements={
     *          "operation": "up|down|remove",
     *          "cartItemId": "\d+"
     *     }
     * )
     */
    public function handleCartItem($operation, $cartItemId)
    {
        $this->isTokenValid();

        /** @var CartItem $CartItem */
        $CartItem = $this->cartItemRepository->find($cartItemId);

        if (is_null($CartItem)) {
            return $this->redirectToRoute('cart');
        }

        // 明細の増減・削除
        switch ($operation) {
            case 'up':
                $CartItem->setQuantity($CartItem->getQuantity() + 1);
                $this->entityManager->flush($CartItem);
                break;
            case 'down':
                $CartItem->setQuantity($CartItem->getQuantity() - 1);
                $this->entityManager->flush($CartItem);
                break;
            case 'remove':
                $this->cartService->removeCartItem($CartItem);
                break;
        }

        $Carts = $this->cartService->getCarts();
        $this->execPurchaseFlow($Carts);

        return $this->redirectToRoute('cart');
    }
}
