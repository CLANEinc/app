<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\Rankingmanual4\Form\Type\Admin;

use Eccube\Common\EccubeConfig;
use Plugin\Rankingmanual4\Entity\RankingmanualProductConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

/**
 * Class RankingmanualProductConfigType.
 */
class RankingmanualProductConfigType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * RankingmanualProductConfigType constructor.
     *
     * @param EccubeConfig $eccubeConfig
     */
    public function __construct(EccubeConfig $eccubeConfig)
    {
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * Build form.
     *
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('rankingmanual_product_title', TextType::class, [
                'required' => false,
            ])
            ->add('rankingmanual_product_disp_title', ChoiceType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'choices' => array(
                    '表示' => '1',
                    '非表示' => '0'
                ),
                'multiple'  => false,
                'expanded' => true,
            ])
            ->add('rankingmanual_product_disp_price', ChoiceType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'choices' => array(
                    '表示' => '1',
                    '非表示' => '0'
                ),
                'multiple'  => false,
                'expanded' => true,
            ])
            ->add('rankingmanual_product_disp_description_detail', ChoiceType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'choices' => array(
                    '表示' => '1',
                    '非表示' => '0'
                ),
                'multiple'  => false,
                'expanded' => true,
            ])
            ->add('rankingmanual_product_disp_code', ChoiceType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'choices' => array(
                    '表示' => '1',
                    '非表示' => '0'
                ),
                'multiple'  => false,
                'expanded' => true,
            ])
            ->add('rankingmanual_product_show_type', ChoiceType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'choices' => array(
                    'スライド' => '1',
                    '横並び' => '0'
                ),
                'multiple'  => false,
                'expanded' => true,
            ])
            ->add('rankingmanual_product_rankingmanual_comment', ChoiceType::class, [
                'constraints' => [
                    new Assert\NotBlank(),
                ],
                'choices' => array(
                    '表示' => '1',
                    '非表示' => '0'
                ),
                'multiple'  => false,
                'expanded' => true,
            ])
        ;
    }

    /**
     * Config.
     *
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RankingmanualProductConfig::class,
        ]);
    }
}
