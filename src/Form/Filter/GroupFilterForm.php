<?php

declare(strict_types = 1);

namespace App\Form\Filter;

use App\DataTransferObjects\GroupFilterDto;
use App\Enum\GroupTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class GroupFilterForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod(Request::METHOD_GET)
            ->add('title', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'choices' => GroupTypeEnum::getArrayCases(),
                'autocomplete' => true,
            ])
            ->add('country', CountryType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'autocomplete' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => GroupFilterDto::class,
        ]);
    }
}
