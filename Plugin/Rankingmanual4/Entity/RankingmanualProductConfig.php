<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Rankingmanual4\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * RankingmanualProductConfig
 *
 * @ORM\Table(name="plg_rankingmanual_product_config")
 * @ORM\Entity(repositoryClass="Plugin\Rankingmanual4\Repository\RankingmanualProductConfigRepository")
 */
class RankingmanualProductConfig extends AbstractEntity
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer", options={"unsigned":true})
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="rankingmanual_product_title", type="text", nullable=true)
     */
    private $rankingmanual_product_title;

    /**
     * @var int
     *
     * @ORM\Column(name="rankingmanual_product_disp_title", type="smallint", nullable=false, options={"unsigned":true})
     */
    private $rankingmanual_product_disp_title;

    /**
     * @var int
     *
     * @ORM\Column(name="rankingmanual_product_disp_price", type="smallint", nullable=false, options={"unsigned":true})
     */
    private $rankingmanual_product_disp_price;

    /**
     * @var int
     *
     * @ORM\Column(name="rankingmanual_product_disp_description_detail", type="smallint", nullable=false, options={"unsigned":true})
     */
    private $rankingmanual_product_disp_description_detail;

    /**
     * @var int
     *
     * @ORM\Column(name="rankingmanual_product_disp_code", type="smallint", nullable=false, options={"unsigned":true})
     */
    private $rankingmanual_product_disp_code;

    /**
     * @var int
     *
     * @ORM\Column(name="rankingmanual_product_show_type", type="smallint", nullable=false, options={"unsigned":true})
     */
    private $rankingmanual_product_show_type;

    /**
     * @var int
     *
     * @ORM\Column(name="rankingmanual_product_rankingmanual_comment", type="smallint", nullable=false, options={"unsigned":true})
     */
    private $rankingmanual_product_rankingmanual_comment;

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
    public function getRankingmanualProductTitle()
    {
        return $this->rankingmanual_product_title;
    }

    /**
     * @param string $rankingmanual_product_title
     */
    public function setRankingmanualProductTitle($rankingmanual_product_title)
    {
        $this->rankingmanual_product_title = $rankingmanual_product_title;
    }

    /**
     * @return int
     */
    public function getRankingmanualProductDispTitle()
    {
        return $this->rankingmanual_product_disp_title;
    }

    /**
     * @param int $rankingmanual_product_disp_title
     */
    public function setRankingmanualProductDispTitle($rankingmanual_product_disp_title)
    {
        $this->rankingmanual_product_disp_title = $rankingmanual_product_disp_title;
    }

    /**
     * @return int
     */
    public function getRankingmanualProductDispPrice()
    {
        return $this->rankingmanual_product_disp_price;
    }

    /**
     * @param int $rankingmanual_product_disp_price
     */
    public function setRankingmanualProductDispPrice($rankingmanual_product_disp_price)
    {
        $this->rankingmanual_product_disp_price = $rankingmanual_product_disp_price;
    }

    /**
     * @return int
     */
    public function getRankingmanualProductDispDescriptionDetail()
    {
        return $this->rankingmanual_product_disp_description_detail;
    }

    /**
     * @param int $rankingmanual_product_disp_description_detail
     */
    public function setRankingmanualProductDispDescriptionDetail($rankingmanual_product_disp_description_detail)
    {
        $this->rankingmanual_product_disp_description_detail = $rankingmanual_product_disp_description_detail;
    }

    /**
     * @return int
     */
    public function getRankingmanualProductDispCode()
    {
        return $this->rankingmanual_product_disp_code;
    }

    /**
     * @param int $rankingmanual_product_disp_code
     */
    public function setRankingmanualProductDispCode($rankingmanual_product_disp_code)
    {
        $this->rankingmanual_product_disp_code = $rankingmanual_product_disp_code;
    }

    /**
     * @return int
     */
    public function getRankingmanualProductShowType()
    {
        return $this->rankingmanual_product_show_type;
    }

    /**
     * @param int $rankingmanual_product_show_type
     */
    public function setRankingmanualProductShowType($rankingmanual_product_show_type)
    {
        $this->rankingmanual_product_show_type = $rankingmanual_product_show_type;
    }

    /**
     * @return int
     */
    public function getRankingmanualProductRankingmanualComment()
    {
        return $this->rankingmanual_product_rankingmanual_comment;
    }

    /**
     * @param int $rankingmanual_product_show_type
     */
    public function setRankingmanualProductRankingmanualComment($rankingmanual_product_rankingmanual_comment)
    {
        $this->rankingmanual_product_rankingmanual_comment = $rankingmanual_product_rankingmanual_comment;
    }

}
