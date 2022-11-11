<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Rankingmanual4\Controller\Admin;

use Plugin\Rankingmanual4\Form\Type\Admin\RankingmanualProductConfigType;
use Plugin\Rankingmanual4\Repository\RankingmanualProductConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ConfigController.
 */
class ConfigController extends \Eccube\Controller\AbstractController
{

    protected $configRepository;

    public function __construct(RankingmanualProductConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/rankingmanual4/config", name="rankingmanual4_admin_config")
     * @Template("@Rankingmanual4/admin/config.twig")
     *
     * @param Request $request
     * @param RankingmanualProductConfigRepository $configRepository
     *
     * @return array
     */
    public function index(Request $request, RankingmanualProductConfigRepository $configRepository)
    {
        $Config = $configRepository->get();
        $form = $this->createForm(RankingmanualProductConfigType::class, $Config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Config = $form->getData();
            $this->entityManager->persist($Config);
            $this->entityManager->flush($Config);

            log_info('Etuna checked item config', ['status' => 'Success']);
            $this->addSuccess('登録しました。', 'admin');

            return $this->redirectToRoute('rankingmanual4_admin_config');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
