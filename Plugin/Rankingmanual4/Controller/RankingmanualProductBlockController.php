<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Rankingmanual4\Controller;

use Eccube\Controller\AbstractController;
use Plugin\Rankingmanual4\Repository\RankingmanualProductConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Eccube\Repository\ProductRepository;


/**
 * Class RankingmanualProductBlockController.
 */
class RankingmanualProductBlockController extends AbstractController
{

    /**
     * @Route("/block/rankingmanual_product_block", name="block_rankingmanual_product_block")
     * @Template("Block/rankingmanual_product_block.twig")
     */
    public function index(Request $request, RankingmanualProductConfigRepository $configRepository)
    {
		$Config = $configRepository->get();
        return [
            'Config' => $Config,
        ];
    }
}
