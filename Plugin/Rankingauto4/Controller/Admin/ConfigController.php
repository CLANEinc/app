<?php

namespace Plugin\Rankingauto4\Controller\Admin;

use Plugin\Rankingauto4\Form\Type\Admin\RankingautoConfigType;
use Plugin\Rankingauto4\Repository\RankingautoConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ConfigController.
 */
class ConfigController extends \Eccube\Controller\AbstractController
{
    /**
     * @Route("/%eccube_admin_route%/rankingauto4/config", name="rankingauto4_admin_config")
     * @Template("@Rankingauto4/admin/config.twig")
     *
     * @param Request $request
     * @param RankingautoConfigRepository $configRepository
     *
     * @return array
     */
    public function index(Request $request, RankingautoConfigRepository $configRepository)
    {
        $Config = $configRepository->get();
        $form = $this->createForm(RankingautoConfigType::class, $Config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Config = $form->getData();
            $this->entityManager->persist($Config);
            $this->entityManager->flush($Config);

            log_info('sales ranking config', ['status' => 'Success']);
            $this->addSuccess('rankingauto.admin.save.complete', 'admin');

            return $this->redirectToRoute('rankingauto4_admin_config');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
