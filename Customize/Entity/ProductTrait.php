<?php
namespace Customize\Entity;

use Doctrine\ORM\Mapping as ORM;
use Eccube\Annotation\EntityExtension;

//以下の記述でベースになっているentityを宣言する。

/**
  * @EntityExtension("Eccube\Entity\Product")
 */

//アットマークORMはデータベースのカラムを追加する記述。
trait ProductTrait{
    /**
     * @var string|null
     * 
     * @ORM\Column(name="production_area",type="string",length=255,nullable=true)
     */
    private $production_area=null;

    /**
     * Set production_area.
     * 
     * @param string $production_area|null
     * 
     * @return Product
     */
    public function setProductionArea($production_area=null){
        $this->production_area=$production_area;
        return $this;
    }
    /**
     * Get production_area.
     * 
     * @return string
     */
    public function getProductionArea(){
        return $this->production_area;
    }

    /**
     * 引数文字列を含むカテゴリに属しているか
     *
     * @return boolean
     */
    protected function belongsCategory ($categoryName, $strict = false) {
        foreach ($this->getProductCategories() as $pc) {
            if ($strict) {
                if ($pc->getCategory()->getName() === $categoryName) {
                    return true;
                }
            } else {
                if (strpos($pc->getCategory()->getName(), $categoryName) !== false) {
                    return true;
                }
            }
        }
        return false;
    }

    public function isCurtain () {
        return $this->belongsCategory('カーテン');
    }

    public function isShade () {
        return $this->belongsCategory('シェード');
    }

    public function isDrapeCurtain () {
        return $this->belongsCategory('ドレープカーテン', true);
    }

    public function isLaceCurtain () {
        return $this->belongsCategory('レースカーテン', true);
    }

    public function isSE () {
        return $this->belongsCategory('SE', true);
    }

    public function isSE001 () {
        return $this->belongsCategory('SE001', true);
    }

    public function isFireProof () {
        return $this->belongsCategory('防炎', true);
    }

    public function getCategoryNameForPrice () {
        $categoryName = '';
        if ($this->isDrapeCurtain()) {
            $categoryName = 'ドレープカーテン';
        }
        if ($this->isLaceCurtain()) {
            $categoryName = 'レースカーテン';
        }
        if ($this->isShade()) {
            $categoryName = 'シェード';
        }
        if ($this->isSE()) {
            $categoryName .= 'SE';
        }
        if ($this->isSE001()) {
            $categoryName .= 'SE001';
        }
        return $categoryName;
    }
}
