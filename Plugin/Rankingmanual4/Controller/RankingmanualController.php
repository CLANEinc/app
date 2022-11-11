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
use Eccube\Form\Type\Admin\SearchProductType;
use Plugin\Rankingmanual4\Entity\RankingmanualProduct;
use Plugin\Rankingmanual4\Form\Type\RankingmanualProductType;
use Plugin\Rankingmanual4\Repository\RankingmanualProductRepository;
use Plugin\Rankingmanual4\Service\RankingmanualService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RankingmanualController.
 */
class RankingmanualController extends AbstractController
{
    /**
     * @var RankingmanualProductRepository
     */
    private $rankingmanualProductRepository;

    /**
     * @var RankingmanualService
     */
    private $rankingmanualService;

    /**
     * RankingmanualController constructor.
     *
     * @param RankingmanualProductRepository $rankingmanualProductRepository
     * @param RankingmanualService $rankingmanualService
     */
    public function __construct(RankingmanualProductRepository $rankingmanualProductRepository, RankingmanualService $rankingmanualService)
    {
        $this->rankingmanualProductRepository = $rankingmanualProductRepository;
        $this->rankingmanualService = $rankingmanualService;
    }

    /**
     * 手動ランキング商品一覧.
     *
     * @param Request     $request
     *
     * @return array
     * @Route("/%eccube_admin_route%/plugin/rankingmanual", name="plugin_rankingmanual_list")
     * @Template("@Rankingmanual4/admin/index.twig")
     */
    public function index(Request $request)
    {
        $pagination = $this->rankingmanualProductRepository->getRankingmanualList();

        return [
            'pagination' => $pagination,
            'total_item_count' => count($pagination),
        ];
    }

    /**
     * Create & Edit.
     *
     * @param Request     $request
     * @param int         $id
     *
     * @throws \Exception
     *
     * @return array|RedirectResponse
     * @Route("/%eccube_admin_route%/plugin/rankingmanual/new", name="plugin_rankingmanual_new")
     * @Route("/%eccube_admin_route%/plugin/rankingmanual/{id}/edit", name="plugin_rankingmanual_edit", requirements={"id" = "\d+"})
     * @Template("@Rankingmanual4/admin/regist.twig")
     */
    public function edit(Request $request, $id = null)
    {
        /* @var RankingmanualProduct $Rankingmanual */
        $Rankingmanual = null;
        $Product = null;
        if (!is_null($id)) {
            // IDから手動ランキング商品情報を取得する
            $Rankingmanual = $this->rankingmanualProductRepository->find($id);

            if (!$Rankingmanual) {
                $this->addError('plugin_rankingmanual.admin.not_found', 'admin');
                log_info('The rankingmanual product is not found.', ['Rankingmanual id' => $id]);

                return $this->redirectToRoute('plugin_rankingmanual_list');
            }

            $Product = $Rankingmanual->getProduct();
        }

        // formの作成
        /* @var Form $form */
        $form = $this->formFactory
            ->createBuilder(RankingmanualProductType::class, $Rankingmanual)
            ->getForm();

        $form->handleRequest($request);
        $data = $form->getData();
        if ($form->isSubmitted() && $form->isValid()) {
            $service = $this->rankingmanualService;
            if (is_null($data['id'])) {
                if ($status = $service->createRankingmanual($data)) {
                    $this->addSuccess('plugin_rankingmanual.admin.register.success', 'admin');
                    log_info('Add the new rankingmanual product success.', ['Product id' => $data['Product']->getId()]);
                }
            } else {
                if ($status = $service->updateRankingmanual($data)) {
                    $this->addSuccess('plugin_rankingmanual.admin.update.success', 'admin');
                    log_info('Update the rankingmanual product success.', ['Rankingmanual id' => $Rankingmanual->getId(), 'Product id' => $data['Product']->getId()]);
                }
            }

            if (!$status) {
                $this->addError('plugin_rankingmanual.admin.not_found', 'admin');
                log_info('Failed the rankingmanual product updating.', ['Product id' => $data['Product']->getId()]);
            }

            return $this->redirectToRoute('plugin_rankingmanual_list');
        }

        if (!empty($data['Product'])) {
            $Product = $data['Product'];
        }

        $arrProductIdByRankingmanual = $this->rankingmanualProductRepository->getRankingmanualProductIdAll();

        return $this->registerView(
            [
                'form' => $form->createView(),
                'rankingmanual_products' => json_encode($arrProductIdByRankingmanual),
                'Product' => $Product,
            ]
        );
    }

    /**
     * 手動ランキング商品の削除.
     *
     * @param Request     $request
     * @param RankingmanualProduct $RankingmanualProduct
     *
     * @throws \Exception
     *
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     * @Route("/%eccube_admin_route%/plugin/rankingmanual/{id}/delete", name="plugin_rankingmanual_delete", requirements={"id" = "\d+"}, methods={"DELETE"})
     */
    public function delete(Request $request, RankingmanualProduct $RankingmanualProduct)
    {
        // Valid token
        $this->isTokenValid();
        // 手動ランキング商品情報を削除する
        if ($this->rankingmanualProductRepository->deleteRankingmanual($RankingmanualProduct)) {
            log_info('The rankingmanual product delete success!', ['Rankingmanual id' => $RankingmanualProduct->getId()]);
            $this->addSuccess('plugin_rankingmanual.admin.delete.success', 'admin');
        } else {
            $this->addError('plugin_rankingmanual.admin.not_found', 'admin');
            log_info('The rankingmanual product is not found.', ['Rankingmanual id' => $RankingmanualProduct->getId()]);
        }

        return $this->redirectToRoute('plugin_rankingmanual_list');
    }

    /**
     * Move rank with ajax.
     *
     * @param Request     $request
     *
     * @throws \Exception
     *
     * @return Response
     *
     * @Route("/%eccube_admin_route%/plugin/rankingmanual/sort_no/move", name="plugin_rankingmanual_rank_move")
     */
    public function moveRank(Request $request)
    {
        if ($request->isXmlHttpRequest()) {
            $arrRank = $request->request->all();
            $arrRankMoved = $this->rankingmanualProductRepository->moveRankingmanualRank($arrRank);
            log_info('Rankingmanual move rank', $arrRankMoved);
        }

        return new Response('OK');
    }

    /**
     * 編集画面用のrender.
     *
     * @param array       $parameters
     *
     * @return array
     */
    protected function registerView($parameters = [])
    {
        // 商品検索フォーム
        $searchProductModalForm = $this->formFactory->createBuilder(SearchProductType::class)->getForm();
        $viewParameters = [
            'searchProductModalForm' => $searchProductModalForm->createView(),
        ];
        $viewParameters += $parameters;

        return $viewParameters;
    }
}
