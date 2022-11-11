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

namespace Customize\EventListener;

use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;
use Doctrine\ORM\NoResultException;
use Eccube\Entity\Product;
use Eccube\Repository\ProductRepository;

class HelloListener implements EventSubscriber
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
        log_info('==== prePersist:');
        if ($Product instanceof Product) {
            $this->updateDisplayRank($Product);
        }
    }

    public function preUpdate(LifecycleEventArgs $args)
    {
        /* @var $Product Product */
        $Product = $args->getObject();
        log_info('==== preUpdate');
        if ($Product instanceof Product) {
            $this->updateDisplayRank($Product);
        }
    }

    private function updateDisplayRank(Product $Product)
    {
        if (is_null($Product->getDisplayRank())) {
            log_info('==== updateDisplayRank');
        }else{
        log_info('==== rank is not null');
        }
    }
}
