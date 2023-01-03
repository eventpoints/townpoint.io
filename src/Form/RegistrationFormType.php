<?php

declare(strict_types=1);

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Contracts\Translation\TranslatorInterface;

class RegistrationFormType extends AbstractType
{


    public function __construct(
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class, [
                'attr' => [
                    'placeholder' => 'First Name',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('lastName', TextType::class, [
                'attr' => [
                    'placeholder' => 'Last Name',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('gender', ChoiceType::class, [
                'attr' => [
                    'placeholder' => 'Gender',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'choices' => [
                    'male' => 'male',
                    'female' => 'female',
                    'couple' => 'couple',
                ],
            ])
            ->add('age', NumberType::class, [
                'attr' => [
                    'placeholder' => 'age',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('email', EmailType::class, [
                'attr' => [
                    'placeholder' => 'E-mail address',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('countryOfOrigin', CountryType::class, [
                'autocomplete' => true,
                'label' => false,
                'placeholder' => $this->translator->trans('country-of-origin'),
                'data' => null,
                'attr' => [
                    'class' => 'autocomplete',
                    'autocomplete' => 'off',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('currentCountry', CountryType::class, [
                'autocomplete' => true,
                'placeholder' => $this->translator->trans('current-country'),
                'label' => false,
                'attr' => [
                    'autocomplete' => 'off',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('plainPassword', PasswordType::class, [
                'label' => $this->translator->trans('password'),
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'mapped' => false,
                'attr' => [
                    'autocomplete' => 'new-password',
                    'placeholder' => 'password',
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter a password',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your password should be at least {{ limit }} characters',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])->add('handle', TextType::class, [
                'label' => $this->translator->trans('handle'),
                'help' => $this->translator->trans('handle-input-helper'),
                'attr' => [
                    'autocomplete' => 'off',
                    'placeholder' => $this->translator->trans('username'),
                    'data-input-validity-checker-target' => 'input',
                    'data-input-validity-checker-path-value' => '/handle/check',
                    'data-action' => 'change->input-validity-checker#inputChange',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('agreeTerms', CheckboxType::class, [
                'mapped' => false,
                'label' => $this->translator->trans('terms-of-use', ),
                'constraints' => [
                    new IsTrue([
                        'message' => $this->translator->trans('terms-required'),
                    ]),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['suggestions']);
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
