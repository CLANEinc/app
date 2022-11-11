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

namespace Plugin\Rankingmanual4\Service;

use Plugin\Rankingmanual4\Entity\RankingmanualProduct;
use Plugin\Rankingmanual4\Repository\RankingmanualProductRepository;

/**
 * Class RankingmanualService.
 */
class RankingmanualService
{
    /**
     * @var RankingmanualProductRepository
     */
    private $rankingmanualProductRepository;

    /**
     * RankingmanualService constructor.
     *
     * @param RankingmanualProductRepository $rankingmanualProductRepository
     */
    public function __construct(RankingmanualProductRepository $rankingmanualProductRepository)
    {
        $this->rankingmanualProductRepository = $rankingmanualProductRepository;
    }

    /**
     * 手動ランキング商品情報を新規登録する
     *
     * @param $data
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function createRankingmanual($data)
    {
        // 手動ランキング商品詳細情報を生成する
        $Rankingmanual = $this->newRankingmanual($data);

        return $this->rankingmanualProductRepository->saveRankingmanual($Rankingmanual);
    }

    /**
     * 手動ランキング商品情報を更新する
     *
     * @param $data
     *
     * @return bool
     *
     * @throws \Exception
     */
    public function updateRankingmanual($data)
    {
        // 手動ランキング商品情報を取得する
        $Rankingmanual = $this->rankingmanualProductRepository->find($data['id']);
        if (!$Rankingmanual) {
            return false;
        }

        // 手動ランキング商品情報を書き換える
        $Rankingmanual->setComment($data['comment']);
        $Rankingmanual->setProduct($data['Product']);

        // 手動ランキング商品情報を更新する
        return $this->rankingmanualProductRepository->saveRankingmanual($Rankingmanual);
    }

    /**
     * 手動ランキング商品情報を生成する
     *
     * @param $data
     *
     * @return RankingmanualProduct
     *
     * @throws \Doctrine\ORM\NoResultException
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    protected function newRankingmanual($data)
    {
        $rank = $this->rankingmanualProductRepository->getMaxRank();

        $Rankingmanual = new RankingmanualProduct();
        $Rankingmanual->setComment($data['comment']);
        $Rankingmanual->setProduct($data['Product']);
        $Rankingmanual->setSortno(($rank ? $rank : 0) + 1);
        $Rankingmanual->setVisible(true);

        return $Rankingmanual;
    }
}
