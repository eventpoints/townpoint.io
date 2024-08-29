<?php

namespace App\Form\Form;

use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DynamicFormBuilder;

class UserAccountFormType extends AbstractType
{
    public function __construct(
        private readonly Security $security
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        if (! $user instanceof User) {
            return;
        }

        $builder = new DynamicFormBuilder($builder);
        $builder->add('firstName', TextType::class, [
            'disabled' => true,
            'row_attr' => [
                'class' => 'form-floating mb-3',
            ],
        ])
            ->add('lastName', TextType::class, [
                'disabled' => true,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('autobio', TextType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('currentCountry', CountryType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'autocomplete' => true,
            ])
            ->add('originCountry', CountryType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'autocomplete' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
