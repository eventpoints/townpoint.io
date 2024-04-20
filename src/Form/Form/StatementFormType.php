<?php

namespace App\Form\Form;

use App\Entity\Statement;
use App\Enum\StatementTypeEnum;
use App\Form\Type\EditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EnumType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', EditorType::class)
            ->add('type', EnumType::class, [
                'label' => false,
                'class' => StatementTypeEnum::class,
                'choice_label' => 'value',
                'autocomplete' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Statement::class,
        ]);
    }
}
