<?php

namespace Plugin\Rankingauto4\Repository;

use Eccube\Repository\AbstractRepository;
use Plugin\Rankingauto4\Entity\RankingautoConfig;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * Rankingauto Config.
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RankingautoConfigRepository extends AbstractRepository
{
    /**
     * RankingautoConfigRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RankingautoConfig::class);
    }

    /**
     * @param int $id
     *
     * @return null|RankingautoConfig
     */
    public function get($id = 1)
    {
        return $this->find($id);
    }
}
