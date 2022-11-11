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

namespace Plugin\ProductDisplayRank4\Repository;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use Eccube\Doctrine\Query\QueryCustomizer;
use Eccube\Repository\QueryKey;

/**
 * おすすめ順.
 */
class AdminProductDisplayCustomizer implements QueryCustomizer
{
    /**
     * @var ConfigRepository
     */
    private $configRepository;

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * ProductDisplayCustomizer constructor.
     */
    public function __construct(
        ConfigRepository $configRepository,
        EntityManagerInterface $entityManager
    ) {
        $this->configRepository = $configRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * @param array  $params
     * @param string $queryKey
     */
    public function customize(QueryBuilder $builder, $params, $queryKey)
    {
        if (isset($params['display_rank_start'])) {
            $builder->andWhere($builder->expr()->gte('p.display_rank', $params['display_rank_start']));
        }

        if (isset($params['display_rank_end'])) {
            $builder->andWhere($builder->expr()->lte('p.display_rank', $params['display_rank_end']));
        }
    }

    /**
     * カスタマイズ対象のキーを返します。
     *
     * @return string
     */
    public function getQueryKey()
    {
        return QueryKey::PRODUCT_SEARCH_ADMIN;
    }
}
