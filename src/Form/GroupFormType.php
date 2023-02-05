<?php

declare(strict_types = 1);

namespace App\Form;

use App\Entity\Group\Group;
use App\Enum\GroupTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
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
            ->add('language', LanguageType::class, [
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
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Group::class,
        ]);
    }
}
