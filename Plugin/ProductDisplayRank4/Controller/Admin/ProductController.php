<?php

/*
 * This file is part of ProductDisplayRank4
 *
 * Copyright(c) U-Mebius Inc. All Rights Reserved.
 *
 * https://umebius.com/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\ProductDisplayRank4\Controller\Admin;

use Eccube\Controller\AbstractController;
use Eccube\Repository\ProductRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * ProductController constructor.
     */
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/product/upadate_rank", name="product_display_rank4_admin_product_update_rank")
     */
    public function update(Request $request)
    {
        if (!($request->isXmlHttpRequest() && $this->isTokenValid())) {
            return $this->json(['status' => 'NG'], 400);
        }

        $id = $request->get('id');
        $rank = $request->get('rank');

        if (!preg_match('/^-?\d+$/', $rank) || !preg_match('/^-?\d+$/', $id)) {
            return $this->json(['status' => 'NG'], 400);
        }

        $Product = $this->productRepository->find($id);
        $Product->setDisplayRank($rank);
        $this->entityManager->flush($Product);

        return new JsonResponse([
            'status' => 'OK',
        ]);
    }
}
