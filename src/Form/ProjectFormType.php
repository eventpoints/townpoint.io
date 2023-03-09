<?php

declare(strict_types = 1);

namespace App\Form;

use App\Entity\Project;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProjectFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'I will have..',
                'help' => 'something that has a defined end',
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('endAt', DateTimeType::class, [
                'input' => 'datetime_immutable',
                'widget' => 'single_text',
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])->add('description', TextareaType::class, [
                'label' => 'project details',
                'help' => 'include how people can help you',
                'attr' => [
                    'data-controller' => 'textarea-autogrow',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Project::class,
        ]);
    }
}
