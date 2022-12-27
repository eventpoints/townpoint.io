<?php

declare(strict_types = 1);

namespace App\Form;

use App\Entity\Snippet;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SnippetFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'attr' => [
                    'placeholder' => 'Give you snippet a name',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('content', FileType::class, [
                'mapped' => false,
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Snippet::class,
        ]);
    }
}
