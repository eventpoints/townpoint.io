<?php

declare(strict_types = 1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\LanguageType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimezoneType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class UserAccountFormType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => $this->translator->trans('email'),
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => $this->translator->trans('firstName'),
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => $this->translator->trans('lastName'),
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('language', LanguageType::class, [
                'label' => $this->translator->trans('language'),
                'choice_loader' => null,
                'choices' => [
                    'English' => 'en',
                    'Češka' => 'cz',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'autocomplete' => true,
            ])
            ->add('currency', CurrencyType::class, [
                'label' => $this->translator->trans('currency'),
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'autocomplete' => true,
            ])
            ->add('timezone', TimezoneType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'attr' => [
                    'data-timezone-target' => 'timezone',
                ],
                'autocomplete' => true,
            ])
            ->add('age', NumberType::class, [
                'attr' => [
                    'placeholder' => $this->translator->trans('age'),
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('countryOfOrigin', CountryType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'autocomplete' => true,
            ])
            ->add('currentCountry', CountryType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'autocomplete' => true,
            ])
            ->add('description', TextareaType::class, [
                'required' => false,
                'label' => $this->translator->trans('one-sentence-about-you'),
                'attr' => [
                    'placeholder' => $this->translator->trans('about'),
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('avatar', FileType::class, [
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
