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

namespace Plugin\ProductDisplayRank4\EventSubscriber;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\NoResultException;
use Eccube\Entity\Product;
use Eccube\Repository\ProductRepository;
use Plugin\ProductDisplayRank4\Entity\Config;

class ProductEventSubscriber implements EventSubscriber
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var ProductRepository
     */
    protected $productRepository;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    public function getSubscribedEvents()
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args)
    {
        /* @var $Product Product */
        $Product = $args->getObject();
        if ($Product instanceof Product) {
            $this->updateDisplayRank($Product);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        /* @var $Product Product */
        $Product = $args->getObject();
        if ($Product instanceof Product) {
            $this->updateDisplayRank($Product);
        }
    }

    private function updateDisplayRank(Product $Product)
    {
        if (is_null($Product->getDisplayRank())) {
            $Config = $this->entityManager->getRepository('Plugin\ProductDisplayRank4\Entity\Config')->find(1);
            $productRepository = $this->entityManager->getRepository(Product::class);

            if (Config::DEFAULT_RANK_TYPE_FIXED == $Config->getDefaultRankType()) {
                $Product->setDisplayRank(
                    ($Config && !is_null($Config->getCsvImportDefaultRank())) ? $Config->getCsvImportDefaultRank() : 0
                );
            } elseif (Config::DEFAULT_RANK_TYPE_MAX_VALUE == $Config->getDefaultRankType()) {
                try {
                    $qb = $productRepository->createQueryBuilder('p');
                    // ポスグレの場合persistでID発行されるが、保存されていないので特に影響はない
                    if ($Product->getId()) {
                        $qb->andWhere($qb->expr()->neq('p.id', $Product->getId()));
                    }
                    $max = $qb
                        ->select('MAX(p.display_rank)')
                        ->getQuery()
                        ->getSingleScalarResult();
                } catch (\Exception $exception) {
                    $max = 0;
                }

                $Product->setDisplayRank($max + $Config->getCsvImportDefaultRank());
            } elseif (Config::DEFAULT_RANK_TYPE_MIN_VALUE == $Config->getDefaultRankType()) {
                try {
                    $qb = $productRepository->createQueryBuilder('p');
                    // ポスグレの場合persistでID発行されるが、保存されていないので特に影響はない
                    if ($Product->getId()) {
                        $qb->andWhere($qb->expr()->neq('p.id', $Product->getId()));
                    }
                    $min = $qb
                        ->select('MIN(p.display_rank)')
                        ->getQuery()
                        ->getSingleScalarResult();
                } catch (NoResultException $exception) {
                    $min = 0;
                }

                $Product->setDisplayRank($min - $Config->getCsvImportDefaultRank());
            }
        }
    }
}
