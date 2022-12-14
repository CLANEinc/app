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

namespace Customize\Service\Cart;

use Eccube\Entity\CartItem;
use Eccube\Service\Cart\CartItemComparator;

class ProductClassAndOptionComparator implements CartItemComparator
{
    /**
     * @param CartItem $Item1 明細1
     * @param CartItem $Item2 明細2
     *
     * @return boolean 同じ明細になる場合はtrue
     */
    public function compare(CartItem $Item1, CartItem $Item2)
    {
        $ProductClass1 = $Item1->getProductClass();
        $ProductClass2 = $Item2->getProductClass();
        $product_class_id1 = $ProductClass1 ? (string) $ProductClass1->getId() : null;
        $product_class_id2 = $ProductClass2 ? (string) $ProductClass2->getId() : null;

        if ($product_class_id1 === $product_class_id2) {
            if(method_exists($Item1, 'getOptionSerial')){
                return $Item1->getOptionSerial() === $Item2->getOptionSerial();
            }else{
                return true;
            }
        }
        return false;
    }
}