<?php

declare(strict_types = 1);

namespace App\Form\Business;

use App\Entity\Business\Business;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BusinessType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('description')
            ->add('type')
            ->add('foundedAt')
            ->add('createdAt')
            ->add('address')
            ->add('phoneNumber')
            ->add('email')
            ->add('website')
            ->add('owner')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Business::class,
        ]);
    }
}
