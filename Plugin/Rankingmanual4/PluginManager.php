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

namespace Plugin\Rankingmanual4;

use Eccube\Application;
use Eccube\Entity\Block;
use Eccube\Entity\BlockPosition;
use Eccube\Entity\Layout;
use Eccube\Entity\Master\DeviceType;
use Eccube\Plugin\AbstractPluginManager;
use Eccube\Repository\BlockPositionRepository;
use Eccube\Repository\BlockRepository;
use Eccube\Repository\LayoutRepository;
use Eccube\Repository\Master\DeviceTypeRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

use Doctrine\ORM\EntityManagerInterface;

use Eccube\Common\EccubeConfig;
use Eccube\Util\EntityUtil;
use Plugin\Rankingmanual4\Entity\RankingmanualProductConfig;
use Plugin\Rankingmanual4\Repository\RankingmanualProductConfigRepository;

/**
 * Class PluginManager.
 */
class PluginManager extends AbstractPluginManager
{
    /**
     * @var string コピー元ブロックファイル
     */
    private $originBlock;

    /**
     * @var string ブロック名
     */
    private $blockName = '手動ランキング商品';

    /**
     * @var string ブロックファイル名
     */
    private $blockFileName = 'rankingmanual_product_block';

    /**
     * PluginManager constructor.
     */
    public function __construct()
    {
        // コピー元ブロックファイル
        $this->originBlock = __DIR__.'/Resource/template/Block/'.$this->blockFileName.'.twig';
    }

    /**
     * @param null $meta
     * @param Application|null $app
     * @param ContainerInterface $container
     *
     * @throws \Exception
     */
    public function uninstall(array $meta, ContainerInterface $container)
    {
        // ブロックの削除
        $this->removeDataBlock($container);
        $this->removeBlock($container);
    }

    /**
     * @param null|array $meta
     * @param ContainerInterface $container
     *
     * @throws \Exception
     */
    public function enable(array $meta = null, ContainerInterface $container)
    {
        $this->copyBlock($container);
        $Block = $container->get(BlockRepository::class)->findOneBy(['file_name' => $this->blockFileName]);
        if (is_null($Block)) {
            // pagelayoutの作成
            $this->createDataBlock($container);
        }

        // プラグイン設定を追加
		$em = $container->get('doctrine.orm.entity_manager');
        $Config = $this->createConfig($em);
    }

    /**
     * @param array|null $meta
     * @param ContainerInterface $container
     */
    public function disable(array $meta = null, ContainerInterface $container)
    {
        $this->removeDataBlock($container);
    }

    /**
     * @param array|null $meta
     * @param ContainerInterface $container
     */
    public function update(array $meta = null, ContainerInterface $container)
    {
        $this->copyBlock($container);
    }

    /**
     * ブロックを登録.
     *
     * @param ContainerInterface $container
     *
     * @throws \Exception
     */
    private function createDataBlock(ContainerInterface $container)
    {
        $em = $container->get('doctrine.orm.entity_manager');
        $DeviceType = $container->get(DeviceTypeRepository::class)->find(DeviceType::DEVICE_TYPE_PC);

        try {
            /** @var Block $Block */
            $Block = $container->get(BlockRepository::class)->newBlock($DeviceType);

            // Blockの登録
            $Block->setName($this->blockName)
                ->setFileName($this->blockFileName)
                ->setUseController(false)
                ->setDeletable(false);
            $em->persist($Block);
            $em->flush($Block);

            // check exists block position
            $blockPos = $container->get(BlockPositionRepository::class)->findOneBy(['Block' => $Block]);
            if ($blockPos) {
                return;
            }

        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * ブロックを削除.
     *
     * @param ContainerInterface $container
     *
     * @throws \Exception
     */
    private function removeDataBlock(ContainerInterface $container)
    {
        // Blockの取得(file_nameはアプリケーションの仕組み上必ずユニーク)
        /** @var \Eccube\Entity\Block $Block */
        $Block = $container->get(BlockRepository::class)->findOneBy(['file_name' => $this->blockFileName]);

        if (!$Block) {
            return;
        }

        $em = $container->get('doctrine.orm.entity_manager');
        try {
            // BlockPositionの削除
            $blockPositions = $Block->getBlockPositions();
            /** @var \Eccube\Entity\BlockPosition $BlockPosition */
            foreach ($blockPositions as $BlockPosition) {
                $Block->removeBlockPosition($BlockPosition);
                $em->remove($BlockPosition);
            }

            // Blockの削除
            $em->remove($Block);
            $em->flush();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * Copy block template.
     *
     * @param ContainerInterface $container
     */
    private function copyBlock(ContainerInterface $container)
    {
        $templateDir = $container->getParameter('eccube_theme_front_dir');
        // ファイルコピー
        $file = new Filesystem();

        if (!$file->exists($templateDir.'/Block/'.$this->blockFileName.'.twig')) {
            // ブロックファイルをコピー
            $file->copy($this->originBlock, $templateDir.'/Block/'.$this->blockFileName.'.twig');
        }
    }

    /**
     * Remove block template.
     *
     * @param ContainerInterface $container
     */
    private function removeBlock(ContainerInterface $container)
    {
        $templateDir = $container->getParameter('eccube_theme_front_dir');
        $file = new Filesystem();
        $file->remove($templateDir.'/Block/'.$this->blockFileName.'.twig');
    }

    protected function createConfig(EntityManagerInterface $em)
    {
        $Config = $em->find(RankingmanualProductConfig::class, 1);
        if ($Config) {
            return $Config;
        }
        $Config = new RankingmanualProductConfig();
        $Config->setRankingmanualProductTitle('売上ランキング');
        $Config->setRankingmanualProductDispTitle(1);
        $Config->setRankingmanualProductDispPrice(1);
        $Config->setRankingmanualProductDispDescriptionDetail(0);
        $Config->setRankingmanualProductDispCode(0);
        $Config->setRankingmanualProductShowType(1);
        $Config->setRankingmanualProductRankingmanualComment(0);

        $em->persist($Config);
        $em->flush($Config);

        return $Config;
    }
}
