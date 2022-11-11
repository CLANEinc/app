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

namespace Plugin\ProductDisplayRank4\Form\Extension;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Form\Type\Admin\SearchProductType;
use Symfony\Component\Form\AbstractTypeExtension;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

/**
 * Class ProductTypeExtension.
 *
 * @FormExtension
 */
class SearchProductTypeExtension extends AbstractTypeExtension
{
    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager
    ) {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('display_rank_start', IntegerType::class, [
                'label' => '表示順(開始)',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => "/^\d+$/u",
                        'message' => 'form_error.numeric_only',
                    ]),
                    new Length(['max' => 11]),
                ],
                'eccube_form_options' => [
                    'auto_render' => false,
                ],
            ])
            ->add('display_rank_end', IntegerType::class, [
                'label' => '表示順(終了)',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => "/^\d+$/u",
                        'message' => 'form_error.numeric_only',
                    ]),
                    new Length(['max' => 11]),
                ],
                'eccube_form_options' => [
                    'auto_render' => false,
                ],
            ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
    }

    public function getExtendedType()
    {
        return SearchProductType::class;
    }

    public function getExtendedTypes()
    {
        return [SearchProductType::class];
    }
}
