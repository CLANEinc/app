<?php

namespace Plugin\Rankingauto4;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\Block;
use Eccube\Entity\BlockPosition;
use Eccube\Entity\Layout;
use Eccube\Entity\Master\DeviceType;
use Eccube\Plugin\AbstractPluginManager;
use Eccube\Repository\BlockPositionRepository;
use Eccube\Repository\BlockRepository;
use Eccube\Repository\LayoutRepository;
use Eccube\Repository\Master\DeviceTypeRepository;
use Plugin\Rankingauto4\Entity\RankingautoConfig;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

class PluginManager extends AbstractPluginManager
{
    /**
     * @var string コピー元ブロックファイル
     */
    private $originBlock;

    /**
     * @var string ブロック名
     */
    private $blockName = '自動売上ランキング';

    /**
     * @var string ブロックファイル名
     */
    private $blockFileName = 'rankingauto_block';

    /**
     * PluginManager constructor.
     */
    public function __construct()
    {
        // コピー元ブロックファイル
        $this->originBlock = __DIR__.'/Resource/template/Block/'.$this->blockFileName.'.twig';
    }

    /**
     * @param array $meta
     * @param ContainerInterface $container
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
    public function enable(array $meta, ContainerInterface $container)
    {
        $em = $container->get('doctrine.orm.entity_manager');

        // プラグイン設定を追加
        $this->createConfig($em);

        $this->copyBlock($container);
        $Block = $container->get(BlockRepository::class)->findOneBy(['file_name' => $this->blockFileName]);
        if (is_null($Block)) {
            // pagelayoutの作成
            $this->createDataBlock($container);
        }
    }

    /**
     * @param array|null $meta
     * @param ContainerInterface $container
     * @throws \Exception
     */
    public function disable(array $meta = null, ContainerInterface $container)
    {
        $this->removeDataBlock($container);
    }

    protected function createConfig(EntityManagerInterface $em)
    {
        $Config = $em->find(RankingautoConfig::class, 1);
        if ($Config) {
            return $Config;
        }
        $Config = new RankingautoConfig();
		$Config->setDisplayTitle('売上ランキング');
        $Config->setTerm(30);
        $Config->setDisplayNum(10);
        $Config->setDisplayLayout(1);
        $Config->setDisplayName(1);
        $Config->setDisplayCode(2);
        $Config->setDisplayPrice(1);
        $Config->setDisplayDescriptionList(2);

        $em->persist($Config);
        $em->flush($Config);

        return $Config;
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
                ->setUseController(true)
                ->setDeletable(false);
            $em->persist($Block);
            $em->flush($Block);

            // check exists block position
            //$blockPos = $container->get(BlockPositionRepository::class)->findOneBy(['Block' => $Block]);
            //if ($blockPos) {
                //return;
            //}

            // BlockPositionの登録
            //$blockPos = $container->get(BlockPositionRepository::class)->findOneBy(
                //['section' => Layout::TARGET_ID_MAIN_BOTTOM, 'layout_id' => Layout::DEFAULT_LAYOUT_UNDERLAYER_PAGE],
                //['block_row' => 'DESC']
            //);

            //$BlockPosition = new BlockPosition();

            // ブロックの順序を変更
            //$BlockPosition->setBlockRow(1);
            //if ($blockPos) {
                //$blockRow = $blockPos->getBlockRow() + 1;
                //$BlockPosition->setBlockRow($blockRow);
            //}

            //$LayoutDefault = $container->get(LayoutRepository::class)->find(Layout::DEFAULT_LAYOUT_UNDERLAYER_PAGE);

            //$BlockPosition->setLayout($LayoutDefault)
                //->setLayoutId($LayoutDefault->getId())
                //->setSection(Layout::TARGET_ID_MAIN_BOTTOM)
                //->setBlock($Block)
                //->setBlockId($Block->getId());

            //$em->persist($BlockPosition);
            //$em->flush($BlockPosition);
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
}
