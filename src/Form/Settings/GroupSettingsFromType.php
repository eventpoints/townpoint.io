<?php

declare(strict_types = 1);

namespace App\Form\Settings;

use App\Entity\Group\Group;
use App\Enum\GroupTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupSettingsFromType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'disabled' => true,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('purpose', TextareaType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('country', CountryType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'autocomplete' => true,
            ])
            ->add('type', ChoiceType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'choices' => GroupTypeEnum::getArrayCases(),
                'autocomplete' => true,
            ])
            ->add('country', CountryType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'autocomplete' => true,
            ])
            ->add('isVisible', CheckboxType::class, [
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'required' => false
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
