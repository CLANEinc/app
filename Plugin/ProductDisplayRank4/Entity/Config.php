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

namespace Plugin\ProductDisplayRank4\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Config
 *
 * @ORM\Table(name="plg_product_display_rank4_config")
 * @ORM\Entity(repositoryClass="Plugin\ProductDisplayRank4\Repository\ConfigRepository")
 */
class Config
{
    const ORDER_BY_DESCENDING = 1;
    const ORDER_BY_ASCENDING = 2;

    const SECOND_SORT_UPDATE_DESC = 1;
    const SECOND_SORT_UPDATE_ASC = 2;

    const THIRD_SORT_ID_DESC = 1;
    const THIRD_SORT_ID_ASC = 2;

    const DEFAULT_RANK_TYPE_FIXED = 1;
    const DEFAULT_RANK_TYPE_MAX_VALUE = 2;
    const DEFAULT_RANK_TYPE_MIN_VALUE = 3;

    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"unsigned":true})
     */
    private $product_list_order_by_id;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", options={"unsigned":true})
     */
    private $type;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned":true})
     */
    private $second_sort_type;

    /**
     * @var int
     *
     * @ORM\Column(type="smallint", nullable=true, options={"unsigned":true})
     */
    private $third_sort_type;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false, options={"default" : 1})
     */
    private $default_rank_type = 1;

    /**
     * @var integer
     *
     * @ORM\Column(type="integer", nullable=false, options={"default" : 0})
     */
    private $csv_import_default_rank = 0;

    /**
     * @var integer
     *
     * @ORM\Column(type="smallint", nullable=false, options={"default" : 6})
     */
    private $block_display_number = 6;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     *
     * @return $this;
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return int
     */
    public function getProductListOrderById()
    {
        return $this->product_list_order_by_id;
    }

    /**
     * @param int $product_list_order_by_id
     *
     * @return Config
     */
    public function setProductListOrderById($product_list_order_by_id)
    {
        $this->product_list_order_by_id = $product_list_order_by_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param int $type
     *
     * @return Config
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return int
     */
    public function getSecondSortType()
    {
        return $this->second_sort_type;
    }

    /**
     * @param int $second_sort_type
     *
     * @return Config
     */
    public function setSecondSortType($second_sort_type)
    {
        $this->second_sort_type = $second_sort_type;

        return $this;
    }

    /**
     * @return int
     */
    public function getThirdSortType()
    {
        return $this->third_sort_type;
    }

    /**
     * @param int $third_sort_type
     *
     * @return Config
     */
    public function setThirdSortType($third_sort_type)
    {
        $this->third_sort_type = $third_sort_type;

        return $this;
    }

    /**
     * @return int
     */
    public function getDefaultRankType()
    {
        return $this->default_rank_type;
    }

    /**
     * @param int $default_rank_type
     *
     * @return Config
     */
    public function setDefaultRankType($default_rank_type)
    {
        $this->default_rank_type = $default_rank_type;

        return $this;
    }

    /**
     * @return string
     */
    public function getCsvImportDefaultRank()
    {
        return $this->csv_import_default_rank;
    }

    /**
     * @param string $csv_import_default_rank
     *
     * @return Config
     */
    public function setCsvImportDefaultRank($csv_import_default_rank)
    {
        $this->csv_import_default_rank = $csv_import_default_rank;

        return $this;
    }

    /**
     * @return int
     */
    public function getBlockDisplayNumber()
    {
        return $this->block_display_number;
    }

    /**
     * @param int $block_display_number
     *
     * @return Config
     */
    public function setBlockDisplayNumber($block_display_number)
    {
        $this->block_display_number = $block_display_number;

        return $this;
    }
}
