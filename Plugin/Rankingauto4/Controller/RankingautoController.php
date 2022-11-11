<?php

namespace Plugin\Rankingauto4\Controller;

use Eccube\Controller\AbstractController;
use Eccube\Repository\OrderItemRepository;
use Eccube\Repository\ProductRepository;
use Eccube\Entity\Master\ProductStatus;
use Eccube\Entity\Master\OrderStatus;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;
use Plugin\Rankingauto4\Repository\RankingautoConfigRepository;


/**
 * Class RankingautoController.
 */
class RankingautoController extends AbstractController
{
    /**
     * @Route("/block/rankingauto_block", name="block_rankingauto_block")
     * @Template("@Rankingauto4/Block/rankingauto_block.twig")
     *
     * @param RankingautoConfigRepository $configRepository
     *
     * @return array
     */
    public function index(RankingautoConfigRepository $configRepository)
    {
        $OrderItem = $this->container->get(OrderItemRepository::class);
        $Product = $this->container->get(ProductRepository::class);
        $excludes = [OrderStatus::CANCEL, OrderStatus::PENDING, OrderStatus::PROCESSING, OrderStatus::RETURNED];

        $Config = $configRepository->get();

        $Date = new \DateTime();
        $end = $Date->format("Y-m-d 23:59:59");
        $term = "- " . $Config->getTerm() . " day";
        $start = $Date->modify($term)->format("Y-m-d 00:00:00");

        $query = $OrderItem->createQueryBuilder('oi')
            ->select('SUM(oi.quantity) as total,p.id')
            ->innerJoin('Eccube\Entity\Product', 'p', 'WITH', 'p.id = oi.Product')
            ->innerJoin('Eccube\Entity\Order', 'o', 'WITH', 'oi.Order = o')
            ->where('p.Status = :Disp')
            ->andWhere('o.OrderStatus NOT IN (:excludes)')
            ->andWhere('o.create_date >= :start')
            ->andWhere('o.create_date <= :end')
            ->orderBy('total', 'DESC')
            ->setParameter('Disp', ProductStatus::DISPLAY_SHOW)
            ->setParameter(':excludes', $excludes)
            ->setParameter(':start', $start)
            ->setParameter(':end', $end)
            ->groupBy('p.id')
            ->setMaxResults($Config->getDisplayNum())
            ->getQuery();

        $ranking_products= array();
        $result = $query->getResult();

        foreach ($result as $item) {
            $ranking_products[] = $Product->find($item['id']);
        }

        return [
            'ranking_products' => $ranking_products,
            'display_name_flg' => $Config->getDisplayName(),
            'display_code_flg' => $Config->getDisplayCode(),
            'display_price_flg' => $Config->getDisplayPrice(),
            'display_description_list_flg' => $Config->getDisplayDescriptionList(),
            'display_title' => $Config->getDisplayTitle(),
            'display_layout_flg' => $Config->getDisplayLayout(),
        ];
    }
}
