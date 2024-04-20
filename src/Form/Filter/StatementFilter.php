<?php

namespace App\Form\Filter;

use App\DataTransferObject\StatementFilterDto;
use App\Enum\StatementTypeEnum;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatementFilter extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('keyword', TextType::class)
            ->add('type', EnumType::class, [
                'label' => false,
                'class' => StatementTypeEnum::class,
                'choice_label' => 'value',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => StatementFilterDto::class,
        ]);
    }
}
