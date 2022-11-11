<?php

namespace Plugin\Rankingauto4\Form\Type\Admin;

use Eccube\Common\EccubeConfig;
use Plugin\Rankingauto4\Entity\RankingautoConfig;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class RankingautoConfigType.
 */
class RankingautoConfigType extends AbstractType
{
    /**
     * @var EccubeConfig
     */
    protected $eccubeConfig;

    /**
     * RankingautoConfigType constructor.
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
        $min = $this->eccubeConfig['rankingauto_start_min'];
        $max = $this->eccubeConfig['rankingauto_end_max'];

        $builder
            ->add('term', NumberType::class, [
                'label' => 'rankingauto.admin.config.term',
                'required' => true,
                'constraints' => [
                    new Assert\Length([
                        'max' => 5,
                    ]),
                    new Assert\Regex([
                        'pattern' => "/^\d+$/u",
                        'message' => 'form_error.numeric_only',
                    ]),
                    new Assert\Range(['min' => $min, 'max' => $max]),
                ],
            ])
            ->add('display_num', NumberType::class, [
                'label' => 'rankingauto.admin.config.display_num',
                'required' => true,
                'constraints' => [
                    new Assert\Length([
                        'max' => 5,
                    ]),
                    new Assert\Regex([
                        'pattern' => "/^\d+$/u",
                        'message' => 'form_error.numeric_only',
                    ]),
                    new Assert\Range(['min' => $min, 'max' => $max]),
                ],
            ])
            ->add('display_name', ChoiceType::class, [
                'label' => 'rankingauto.admin.config.display_name',
                'choices' => [
                    'rankingauto.admin.config.in_display' => RankingautoConfig::IN_DISPLAY,
                    'rankingauto.admin.config.out_of_display' => RankingautoConfig::OUT_OF_DISPLAY,
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('display_code', ChoiceType::class, [
                'label' => 'rankingauto.admin.config.display_code',
                'choices' => [
                    'rankingauto.admin.config.in_display' => RankingautoConfig::IN_DISPLAY,
                    'rankingauto.admin.config.out_of_display' => RankingautoConfig::OUT_OF_DISPLAY,
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('display_price', ChoiceType::class, [
                'label' => 'rankingauto.admin.config.display_price',
                'choices' => [
                    'rankingauto.admin.config.in_display' => RankingautoConfig::IN_DISPLAY,
                    'rankingauto.admin.config.out_of_display' => RankingautoConfig::OUT_OF_DISPLAY,
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('display_description_list', ChoiceType::class, [
                'label' => 'rankingauto.admin.config.display_description_list',
                'choices' => [
                    'rankingauto.admin.config.in_display' => RankingautoConfig::IN_DISPLAY,
                    'rankingauto.admin.config.out_of_display' => RankingautoConfig::OUT_OF_DISPLAY,
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('display_title', TextType::class, [
				'label' => 'rankingauto.admin.config.display_title',
                'required' => false,
            ])
            ->add('display_layout', ChoiceType::class, [
                'label' => 'rankingauto.admin.config.display_layout',
                'choices' => [
                    'rankingauto.admin.config.slide' => RankingautoConfig::IN_DISPLAY,
                    'rankingauto.admin.config.sidebyside' => RankingautoConfig::OUT_OF_DISPLAY,
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new Assert\NotBlank(),
                ],
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
            'data_class' => RankingautoConfig::class,
        ]);
    }
}
