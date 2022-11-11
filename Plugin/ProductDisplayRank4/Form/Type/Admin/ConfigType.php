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

namespace Plugin\ProductDisplayRank4\Form\Type\Admin;

use Eccube\Common\EccubeConfig;
use Plugin\ProductDisplayRank4\Entity\Config;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ConfigType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * CustomerType constructor.
     */
    public function __construct(EccubeConfig $eccubeConfig)
    {
        $this->eccubeConfig = $eccubeConfig;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length(['max' => 255]),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    '降順(大きい数字の商品を先に表示)' => Config::ORDER_BY_DESCENDING,
                    '昇順(小さい数字の商品を先に表示)' => Config::ORDER_BY_ASCENDING,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
            ])
            ->add('second_sort_type', ChoiceType::class, [
                'required' => false,
                'choices' => [
                    '商品更新日 降順(新しい商品を先に表示)' => Config::SECOND_SORT_UPDATE_DESC,
                    '商品更新日 昇順(古い商品を先に表示)' => Config::SECOND_SORT_UPDATE_ASC,
                ],
                'constraints' => [
                ],
                'placeholder' => '指定しない',
            ])
            ->add('third_sort_type', ChoiceType::class, [
                'choices' => [
                    '商品ID 降順(大きい商品を先に表示)' => Config::THIRD_SORT_ID_DESC,
                    '商品ID 昇順(古い商品を先に表示)' => Config::THIRD_SORT_ID_ASC,
                ],
                'constraints' => [
                ],
            ])
            ->add('default_rank_type', ChoiceType::class, [
                'required' => true,
                'choices' => [
                    '固定値' => Config::DEFAULT_RANK_TYPE_FIXED,
                    '最も大きい値を基準にする' => Config::DEFAULT_RANK_TYPE_MAX_VALUE,
                    '最も小さい値を基準にする' => Config::DEFAULT_RANK_TYPE_MIN_VALUE,
                ],
                'constraints' => [
                    new NotBlank(),
                ],
                'placeholder' => false,
            ])
            ->add('csv_import_default_rank', IntegerType::class, [
                'constraints' => [
                    new NotBlank(),
                    new Length([
                        'max' => $this->eccubeConfig['eccube_int_len'],
                    ]),
                ],
            ])
            ->add('block_display_number', ChoiceType::class, [
                'required' => true,
                'label' => 'ブロック内商品表示数',
                'constraints' => [
                    new NotBlank(),
                ],
                'choices' => array_combine(range(1, 30), range(1, 30)),
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Config::class,
        ]);
    }
}
