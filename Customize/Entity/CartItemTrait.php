<?php
namespace Customize\Entity;

use Eccube\Annotation\EntityExtension;
use Doctrine\ORM\Mapping as ORM;
use Eccube\Entity\CartItem;

/**
 * @EntityExtension("Eccube\Entity\CartItem")
 */
trait CartItemTrait
{
    /**
     * @var string|null
     *
     * @ORM\Column(name="option_serial", type="string", length=10000, nullable=true)
     */
    private $option_serial;

    /**
     * @var string
     *
     * @ORM\Column(name="price_without_tax", type="decimal", precision=12, scale=2, options={"default":0})
     */
    private $price_without_tax = 0;

    public function getId()
    {
        return $this->id;
    }

    public function setOptionSerial($serial)
    {
        $this->option_serial = $serial;

        return $this;
    }

    public function getOptionSerial()
    {
        return $this->option_serial;
    }

    public function getArrOption()
    {
        return unserialize($this->option_serial);
    }

    /**
     * @param  integer  $price
     *
     * @return CartItem
     */
    public function setPriceWithoutTax($price)
    {
        $this->price_without_tax = $price;

        return $this;
    }

    /**
     * @return string
     */
    public function getPriceWithoutTax()
    {
        return $this->price_without_tax;
    }
}
