<?php
namespace Customize\Entity;

use Eccube\Annotation\EntityExtension;
use Eccube\Common\EccubeConfig;
use Doctrine\ORM\Mapping as ORM;

/**
 * @EntityExtension("Eccube\Entity\Order")
 */
trait OrderTrait
{
    /**
     * Get tax.
     *
     * @return string
     *
     * CSV出力用。内税額。
     */
    public function getInnerTax()
    {
        global $kernel;
        $container = $kernel->getContainer();
        $eccubeConfig = new EccubeConfig($container);
        $taxRate = $eccubeConfig->get('tax_rate');
        return floor($this->total * $taxRate / ($taxRate + 100));
    }
}
