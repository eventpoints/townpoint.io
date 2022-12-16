<?php

namespace App\Form;

use App\Entity\Poll;
use App\Entity\PollOption;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PollFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('motion', TextType::class, [
                'label' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'attr' => [
                    'placeholder' => 'What would you like to ask?'
                ]
            ])
            ->add('endAt', DateTimeType::class, [
                'widget' => 'single_text',
                'input' => 'datetime_immutable'
            ])
            ->add('pollOptions', CollectionType::class, [
                'label' => false,
                'entry_type' => PollOptionFormType::class,
                'allow_add' => true,
                'delete_empty' => true,
                'prototype' => true,
                'by_reference' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Poll::class,
        ]);
    }
}
