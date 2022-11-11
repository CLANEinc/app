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

namespace Plugin\ProductDisplayRank4\Controller;

use Doctrine\ORM\Tools\Pagination\Paginator;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\ProductStatus;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\ProductRepository;
use Plugin\ProductDisplayRank4\Entity\Config;
use Plugin\ProductDisplayRank4\Repository\ConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class BlockController extends AbstractController
{
    /**
     * @var ProductRepository
     */
    protected $productRepository;

    /**
     * @var ConfigRepository
     */
    private $configRepository;

    /**
     * @var BaseInfoRepository
     */
    private $baseInfoRepository;

    /**
     * ProductController constructor.
     */
    public function __construct(
        ProductRepository $productRepository,
        ConfigRepository $configRepository,
        BaseInfoRepository $baseInfoRepository
    ) {
        $this->productRepository = $productRepository;
        $this->configRepository = $configRepository;
        $this->baseInfoRepository = $baseInfoRepository;
    }

    /**
     * @Route("/block/product_display_rank", name="block_product_display_rank")
     *
     * @Template("Block/product_display_rank.twig")
     */
    public function index(Request $request)
    {
        $originOptionNostockHidden = $this->entityManager->getFilters()->isEnabled('option_nostock_hidden');

        // Doctrine SQLFilter
        if ($this->baseInfoRepository->get()->isOptionNostockHidden()) {
            $this->entityManager->getFilters()->enable('option_nostock_hidden');
        }

        $qb = $this->productRepository
            ->createQueryBuilder('p');
        $qb
            ->where('p.Status = :Disp')
            ->setParameter('Disp', ProductStatus::DISPLAY_SHOW);

        /* @var $Config Config */
        $Config = $this->configRepository->findOneBy([]);

        // @see https://github.com/EC-CUBE/ec-cube/issues/1998
        if ($this->entityManager->getFilters()->isEnabled('option_nostock_hidden') == true) {
            $qb->innerJoin('p.ProductClasses', 'pc');
            $qb->andWhere('pc.visible = true');
        }

        if ($Config) {
            if ($Config->getType() == Config::ORDER_BY_DESCENDING) {
                $qb->orderBy('p.display_rank', 'desc');
            } elseif ($Config->getType() == Config::ORDER_BY_ASCENDING) {
                $qb->orderBy('p.display_rank', 'asc');
            }

            if (!is_null($Config->getSecondSortType())) {
                if ($Config->getSecondSortType() == Config::SECOND_SORT_UPDATE_DESC) {
                    $qb->addOrderBy('p.update_date', 'desc');
                } elseif ($Config->getSecondSortType() == Config::SECOND_SORT_UPDATE_ASC) {
                    $qb->addOrderBy('p.update_date', 'asc');
                }
            }

            if (!is_null($Config->getThirdSortType())) {
                if ($Config->getThirdSortType() == Config::THIRD_SORT_ID_DESC) {
                    $qb->addOrderBy('p.id', 'desc');
                } elseif ($Config->getThirdSortType() == Config::THIRD_SORT_ID_ASC) {
                    $qb->addOrderBy('p.id', 'asc');
                }
            }
        }

        $qb->setMaxResults($Config->getBlockDisplayNumber());
        $paginator = new Paginator($qb->getQuery(), $fetchJoinCollection = true);

        $Products = [];

        foreach ($paginator as $Product) {
            $Products[] = $Product;
        }

        if ($originOptionNostockHidden) {
            if (!$this->entityManager->getFilters()->isEnabled('option_nostock_hidden')) {
                $this->entityManager->getFilters()->enable('option_nostock_hidden');
            }
        } else {
            if ($this->entityManager->getFilters()->isEnabled('option_nostock_hidden')) {
                $this->entityManager->getFilters()->disable('option_nostock_hidden');
            }
        }

        return [
            'Products' => $Products,
        ];
    }
}
