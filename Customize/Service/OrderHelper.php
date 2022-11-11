<?php
namespace Customize\Service;
use Eccube\Service\OrderHelper as BaseOrderHelper;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Eccube\Entity\CartItem;
use Eccube\Entity\Master\OrderItemType;
use Eccube\Entity\OrderItem;
class OrderHelper extends BaseOrderHelper
{
    /**
     * @param Collection|ArrayCollection|CartItem[] $CartItems
     *
     * @return OrderItem[]
     */
    protected function createOrderItemsFromCartItems($CartItems)
    {
        $ProductItemType = $this->orderItemTypeRepository->find(OrderItemType::PRODUCT);

        return array_map(function ($item) use ($ProductItemType) {
            /* @var $item CartItem */
            /* @var $ProductClass \Eccube\Entity\ProductClass */
            $ProductClass = $item->getProductClass();
            /* @var $Product \Eccube\Entity\Product */
            $Product = $ProductClass->getProduct();

            $OrderItem = new OrderItem();
            $OrderItem
                ->setProduct($Product)
                ->setProductClass($ProductClass)
                ->setProductName($Product->getName())
                ->setProductCode($ProductClass->getCode())
                ->setPrice($item->getPriceWithoutTax()) // カート内商品の価格をコピーするように変更
                ->setOptionSerial($item->getOptionSerial()) // カート内商品のオプション選択をコピー
                ->setQuantity($item->getQuantity())
                ->setOrderItemType($ProductItemType);

            $ClassCategory1 = $ProductClass->getClassCategory1();
            if (!is_null($ClassCategory1)) {
                $OrderItem->setClasscategoryName1($ClassCategory1->getName());
                $OrderItem->setClassName1($ClassCategory1->getClassName()->getName());
            }
            $ClassCategory2 = $ProductClass->getClassCategory2();
            if (!is_null($ClassCategory2)) {
                $OrderItem->setClasscategoryName2($ClassCategory2->getName());
                $OrderItem->setClassName2($ClassCategory2->getClassName()->getName());
            }

            return $OrderItem;
        }, $CartItems instanceof Collection ? $CartItems->toArray() : $CartItems);
    }

}
