<?php

namespace Plugin\Rankingauto4\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\AbstractEntity;

/**
 * RankingautoConfig
 *
 * @ORM\Table(name="plg_rankingauto_config")
 * @ORM\Entity(repositoryClass="Plugin\Rankingauto4\Repository\RankingautoConfigRepository")
 */
class RankingautoConfig extends AbstractEntity
{
    const IN_DISPLAY = 1;
    const OUT_OF_DISPLAY = 2;

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
     * @ORM\Column(name="term", type="smallint", nullable=true, options={"unsigned":true, "default":30})
     */
    private $term;

    /**
     * @var int
     *
     * @ORM\Column(name="display_num", type="smallint", nullable=true, options={"unsigned":true, "default":5})
     */
    private $display_num;

    /**
     * @var int
     *
     * @ORM\Column(name="display_name", type="smallint")
     */
    private $display_name;

    /**
     * @var int
     *
     * @ORM\Column(name="display_code", type="smallint")
     */
    private $display_code;

    /**
     * @var int
     *
     * @ORM\Column(name="display_price", type="smallint")
     */
    private $display_price;

    /**
     * @var int
     *
     * @ORM\Column(name="display_description_list", type="smallint")
     */
    private $display_description_list;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="create_date", type="datetimetz")
     */
    private $create_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="update_date", type="datetimetz")
     */
    private $update_date;

    /**
     * @var string
     *
     * @ORM\Column(name="display_title", type="text", nullable=true)
     */
    private $display_title;

    /**
     * @var int
     *
     * @ORM\Column(name="display_layout", type="smallint", nullable=false, options={"unsigned":true})
     */
    private $display_layout;

    /**
     * Set rankingauto config id.
     *
     * @param string $id
     *
     * @return RankingautoConfig
     */
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Get term.
     *
     * @return int
     */
    public function getTerm()
    {
        return $this->term;
    }

    /**
     * Set term.
     *
     * @param int $term
     *
     * @return $this
     */
    public function setTerm($term)
    {
        $this->term = $term;

        return $this;
    }

    /**
     * Get display_num.
     *
     * @return int
     */
    public function getDisplayNum()
    {
        return $this->display_num;
    }

    /**
     * Set display_num.
     *
     * @param int $display_num
     *
     * @return $this
     */
    public function setDisplayNum($display_num)
    {
        $this->display_num = $display_num;

        return $this;
    }

    /**
     * Get display_name.
     *
     * @return int
     */
    public function getDisplayName()
    {
        return $this->display_name;
    }

    /**
     * Set display_name.
     *
     * @param int $display_name
     *
     * @return $this
     */
    public function setDisplayName($display_name)
    {
        $this->display_name = $display_name;

        return $this;
    }

    /**
     * Get display_code.
     *
     * @return int
     */
    public function getDisplayCode()
    {
        return $this->display_code;
    }

    /**
     * Set display_code.
     *
     * @param int $display_code
     *
     * @return $this
     */
    public function setDisplayCode($display_code)
    {
        $this->display_code = $display_code;

        return $this;
    }

    /**
     * Get display_price.
     *
     * @return int
     */
    public function getDisplayPrice()
    {
        return $this->display_price;
    }

    /**
     * Set display_price.
     *
     * @param int $display_price
     *
     * @return $this
     */
    public function setDisplayPrice($display_price)
    {
        $this->display_price = $display_price;

        return $this;
    }

    /**
     * Get display_description_list.
     *
     * @return int
     */
    public function getDisplayDescriptionList()
    {
        return $this->display_description_list;
    }

    /**
     * Set display_description_list.
     *
     * @param int $display_description_list
     *
     * @return $this
     */
    public function setDisplayDescriptionList($display_description_list)
    {
        $this->display_description_list = $display_description_list;

        return $this;
    }

    /**
     * Set create_date.
     *
     * @param \DateTime $createDate
     *
     * @return $this
     */
    public function setCreateDate($createDate)
    {
        $this->create_date = $createDate;

        return $this;
    }

    /**
     * Get create_date.
     *
     * @return \DateTime
     */
    public function getCreateDate()
    {
        return $this->create_date;
    }

    /**
     * Set update_date.
     *
     * @param \DateTime $updateDate
     *
     * @return $this
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Get update_date.
     *
     * @return \DateTime
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * @return string
     */
    public function getDisplayTitle()
    {
        return $this->display_title;
    }

    /**
     * @param string $display_title
     */
    public function setDisplayTitle($display_title)
    {
        $this->display_title = $display_title;
    }

    /**
     * @return string
     */
    public function getDisplayLayout()
    {
        return $this->display_layout;
    }

    /**
     * @param string $display_layout
     */
    public function setDisplayLayout($display_layout)
    {
        $this->display_layout = $display_layout;
    }
}
