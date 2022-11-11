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

namespace Plugin\ProductDisplayRank4;

use Eccube\Entity\Block;
use Eccube\Entity\Csv;
use Eccube\Entity\Master\CsvType;
use Eccube\Entity\Master\DeviceType;
use Eccube\Entity\Master\ProductListOrderBy;
use Eccube\Plugin\AbstractPluginManager;
use Plugin\ProductDisplayRank4\Entity\Config;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Filesystem\Filesystem;

/**
 * Class PluginManager.
 */
class PluginManager extends AbstractPluginManager
{
    const VERSION = '1.1.0';
    const PLUGIN_CODE = 'ProductDisplayRank4';

    private $blocks = [
        [
            'file_name' => 'product_display_rank',
            'name' => 'おすすめ順商品',
            'use_controller' => true,
            'deletable' => false,
        ],
    ];

    /**
     * Install the plugin.
     */
    public function install(array $meta, ContainerInterface $container)
    {
    }

    /**
     * Update the plugin.
     */
    public function update(array $meta, ContainerInterface $container)
    {
        $this->createCsvData($container, true);
        $this->createBlocks($container);
        $this->copyTemplateFile($container);
    }

    /**
     * Enable the plugin.
     */
    public function enable(array $meta, ContainerInterface $container)
    {
        $this->createCsvData($container, false);
        $this->createMasterData($container);
        $this->createBlocks($container);
        $this->copyTemplateFile($container);
    }

    /**
     * Disable the plugin.
     */
    public function disable(array $meta, ContainerInterface $container)
    {
        $this->deleteCsvData($container);
        $this->deleteMasterData($container);
        $this->removeBlocks($container);
    }

    /**
     * Uninstall the plugin.
     */
    public function uninstall(array $meta, ContainerInterface $container)
    {
        $this->deleteCsvData($container);
        $this->deleteMasterData($container);
        $this->removeBlocks($container);
    }

    private function createMasterData(ContainerInterface $container)
    {
        $entityManager = $container->get('doctrine')->getManager();

        $Configs = $entityManager->getRepository('Plugin\ProductDisplayRank4\Entity\Config')->findAll();

        $maxRank = 1;

        if (!count($Configs)) {
            $qb = $entityManager->createQueryBuilder();
            $maxId = $qb->select('MAX(m.id)')
                ->from(ProductListOrderBy::class, 'm')
                ->getQuery()
                ->getSingleScalarResult();
            $data = [
                [
                    'className' => 'Eccube\Entity\Master\ProductListOrderBy',
                    'id' => ++$maxId,
                    'sort_no' => ++$maxRank,
                    'name' => 'おすすめ順',
                ],
            ];
        } else {
            /* @var $Configs Config[] */
            foreach ($Configs as $Config) {
                $data[] =
                    [
                        'className' => 'Eccube\Entity\Master\ProductListOrderBy',
                        'id' => $Config->getProductListOrderById(),
                        'sort_no' => ++$maxRank,
                        'name' => $Config->getName(),
                    ]
                ;
            }
        }

        foreach ($data as $row) {
            $Entity = $entityManager->getRepository($row['className'])
                ->find($row['id']);
            if (!$Entity) {
                // 先頭に持ってくる処理を入れる
                $OtherEntities = $entityManager->getRepository($row['className'])->findBy([], ['sort_no' => 'asc']);

                $Entity = new $row['className']();
                $Entity
                    ->setName($row['name'])
                    ->setId($row['id'])
                    ->setSortNo($row['sort_no'])
                ;

                $entityManager->persist($Entity);
                $entityManager->flush($Entity);

                $i = 0;
                foreach ($OtherEntities as $OtherEntity) {
                    $OtherEntity->setSortNo($row['sort_no'] + (++$i));
                    $entityManager->flush($OtherEntity);
                }

                $Config = new Config();
                $Config
                    ->setName($row['name'])
                    ->setProductListOrderById($Entity->getId())
                    ->setType(1)
                    ->setCsvImportDefaultRank(0)
                ;

                $entityManager->persist($Config);
                $entityManager->flush($Config);
            }
        }
    }

    private function deleteMasterData(ContainerInterface $container)
    {
        $entityManager = $container->get('doctrine')->getManager();

        $Configs = $entityManager->getRepository('Plugin\ProductDisplayRank4\Entity\Config')->findAll();

        /* @var $Configs Config[] */
        foreach ($Configs as $Config) {
            $ProductListOrderBy = $entityManager->getRepository('Eccube\Entity\Master\ProductListOrderBy')
                ->find($Config->getProductListOrderById());

            if ($ProductListOrderBy) {
                $entityManager->remove($ProductListOrderBy);
            }
        }
    }

    private function createCsvData(ContainerInterface $container, $checkEnabled)
    {
        $entityManager = $container->get('doctrine')->getManager();
        $entityName = 'Eccube\\\\Entity\\\\Product';
        $fieldName = 'display_rank';
        $dispName = '表示順';

        $Plugin = $entityManager->getRepository('Eccube\Entity\Plugin')->findByCode(self::PLUGIN_CODE);

        if ($checkEnabled && !$Plugin->isEnabled()) {
            return;
        }

        $Csv = $entityManager->getRepository('Eccube\Entity\Csv')->findOneBy([
            'CsvType' => CsvType::CSV_TYPE_PRODUCT,
            'entity_name' => $entityName,
            'field_name' => $fieldName,
            'reference_field_name' => null,
        ]);

        $sortNoMax = $entityManager->getRepository('Eccube\Entity\Csv')->findOneBy(['CsvType' => CsvType::CSV_TYPE_PRODUCT], ['sort_no' => 'DESC']);
        $sortNo = 0;
        if (!is_null($sortNoMax)) {
            $sortNo = $sortNoMax->getSortNo();
        }

        if (!$Csv) {
            $CsvType = $entityManager->getRepository('Eccube\Entity\Master\CsvType')
                ->find(CsvType::CSV_TYPE_PRODUCT);
            $Csv = new Csv();
            $Csv
                ->setCsvType($CsvType)
                ->setCreator(null)
                ->setEntityName($entityName)
                ->setFieldName($fieldName)
                ->setReferenceFieldName(null)
                ->setDispName($dispName)
                ->setEnabled(true)
                ->setSortNo($sortNo + 1);

            $entityManager->persist($Csv);
            $entityManager->flush();
        }
    }

    private function deleteCsvData(ContainerInterface $container)
    {
        $entityManager = $container->get('doctrine')->getManager();
        $entityName = 'Eccube\\\\Entity\\\\Product';
        $fieldName = 'display_rank';

        $Csv = $entityManager->getRepository('Eccube\Entity\Csv')->findOneBy([
            'CsvType' => CsvType::CSV_TYPE_PRODUCT,
            'entity_name' => $entityName,
            'field_name' => $fieldName,
            'reference_field_name' => null,
        ]);

        if ($Csv) {
            $entityManager->remove($Csv);
            $entityManager->flush($Csv);
        }
    }

    /**
     * @param $container ContainerInterface
     */
    private function createBlocks($container)
    {
        $entityManager = $container->get('doctrine')->getManager();

        $DeviceType = $entityManager->getRepository('Eccube\Entity\Master\DeviceType')
            ->find(DeviceType::DEVICE_TYPE_PC);

        foreach ($this->blocks as $block) {
            if (!$Block = $entityManager->getRepository('Eccube\Entity\Block')->findOneBy([
                'file_name' => $block['file_name'],
            ])) {
                $Block = new Block();
                $Block
                    ->setDeviceType($DeviceType)
                    ->setUseController($block['use_controller'])
                    ->setName($block['name'])
                    ->setDeletable($block['deletable'])
                    ->setFileName($block['file_name'])
                ;
                $entityManager->persist($Block);
                $entityManager->flush($Block);
            }
        }
    }

    /**
     * @param $container ContainerInterface
     */
    private function removeBlocks($container)
    {
        $entityManager = $container->get('doctrine')->getManager();

        $Blocks = $entityManager->getRepository('Eccube\Entity\Block')
            ->findBy([
                'file_name' => array_map(function ($block) {
                    return $block['file_name'];
                }, $this->blocks),
            ]);

        foreach ($Blocks as $Block) {
            $entityManager->remove($Block);
        }

        $entityManager->flush();
    }

    private function copyTemplateFile(ContainerInterface $container)
    {
        $templateDir = $container->getParameter('eccube_theme_front_dir');

        foreach ($this->blocks as $block) {
            $targetPath = $templateDir.'/Block/'.$block['file_name'].'.twig';
            if (!file_exists($targetPath)) {
                $file = new Filesystem();
                $file->copy(
                    dirname(__FILE__).'/Resource/template/default/Block/'.$block['file_name'].'.twig',
                    $targetPath
                );
            }
        }
    }
}
