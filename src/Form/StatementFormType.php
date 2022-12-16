<?php

namespace App\Form;

use App\Entity\Statement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class StatementFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('photo', FileType::class,[
                'label'=> false,
                'required' => false,
                'mapped' => false,
                'attr' => [
                    'data-file-input-target' => 'file'
                ]
            ])
            ->add('content', TextType::class, [
                'label' => 'What have you been doing?',
                'attr' => [
                    'placeholder' => 'What have you been doing?',
                    'rows' => 50
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Statement::class,
        ]);
    }
}
