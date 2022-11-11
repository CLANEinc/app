<?php

namespace Customize\Form\Extension;

use Eccube\Form\Type\AddCartType;
use Eccube\Common\EccubeConfig;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\RequestStack;
use Doctrine\ORM\EntityManagerInterface;

class AddCartExtension extends AbstractTypeExtension
{

    /**
     * @var \Doctrine\ORM\EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var array
     */
    protected $eccubeConfig;

    private $requestStack;


    public function __construct(
        EntityManagerInterface $entityManager,
        EccubeConfig $eccubeConfig,
        RequestStack $requestStack
    ) {
        $this->eccubeConfig = $eccubeConfig;
        $this->entityManager = $entityManager;
        $this->requestStack = $requestStack;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $build_options)
    {
        $Product = $build_options['product'];
        $request = $this->requestStack->getMasterRequest();
        $route = $request->get('_route');

        $optionListFull = $this->eccubeConfig['option_list'];
        if ($Product->isCurtain()) {
            $optionList = $optionListFull['カーテン'];
        } else {
            $optionList = $optionListFull['シェード'];
        }
        foreach ($optionList as $optionName => $Option) {
            if(!($Product->getStockFind() || $route === 'admin_order_search_product')) {
                continue;
            }
            if ($Product->isSE001() && $optionName === 'lining') {
                // SE001 にはシングルシェードしか無い & シングルシェードには裏生地が無い
                continue;
            }
            $type = $Option['type'];
            $options = ['mapped' => false];
            $options['label'] = $Option['label'];
            $options['required'] = true;
            $options['constraints'] = [
                new Assert\NotBlank(),
            ];
            if ($type == 'radio') {
                $options['expanded'] = true;
                $options['multiple'] = false;
                $options['placeholder'] = null;
                $optionCategories = [];
                $defaultChoice = null;
                foreach ($Option['items'] as $value => $OptionItem) {
                    if ($Product->isSE001()) {
                        // SE001 にはシングルシェードしか無い
                        if ($optionName === 'shadetype') {
                            if ($value === 'single') {
                                $optionCategories[$OptionItem['label']] = $value;
                            }
                            continue;
                        }
                        // 要確認　SE001 シャープ にはコード式がない？
//                        if ($optionName === 'houseistyle') {
//                            if ($value === 'sharp') {
//                            }
//                        }
                    }
                    $optionCategories[$OptionItem['label']] = $value;
                    if (isset($OptionItem['default'])) {
                        $defaultChoice = $value;
                    }
                }
                $options['choices'] = $optionCategories;
                if(is_null($defaultChoice) && $optionName !== 'lining') { // 裏生地の初期値はなし
                    $options['data'] = current($options['choices']);
                }else{
                    $options['data'] = $defaultChoice;
                }
                $form_type = Type\ChoiceType::class;
            } elseif ($type == 'number') {
                $form_type = Type\IntegerType::class;
                $options['attr'] = ['class' => 'number','maxlength' => $this->eccubeConfig['eccube_int_len']];
                $options['constraints'][] = new Assert\Regex(['pattern' => '/^-{0,1}\d+$/']);
                $min = $Option['min'];
                $max = $Option['max'];
                if(strlen($min) > 0){
                    $options['attr']['min'] = $min;
                    $options['constraints'][] = new Assert\GreaterThanOrEqual(['value' => $min]);
                }
                if(strlen($max) > 0){
                    $options['attr']['max'] = $max;
                    $options['constraints'][] = new Assert\LessThanOrEqual(['value' => $max]);
                }
            }
            log_info("=====".$optionName);
            $builder->add($optionName, $form_type, $options);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function getExtendedType()
    {
        return AddCartType::class;
    }

    public static function getExtendedTypes(): iterable
    {
        return [AddCartType::class];
    }
}
