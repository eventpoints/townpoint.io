<?php

namespace App\Form\Form;

use App\Entity\Country;
use App\Entity\Town;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfonycasts\DynamicForms\DependentField;
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
            'row_attr' => [
                'class' => 'form-floating mb-3',
            ],
        ])
            ->add('lastName', TextType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('autobio', TextType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('currentCountry', EntityType::class, [
                'mapped' => false,
                'class' => Country::class,
                'data' => $user->getCurrentTown()->getCountry(),
                'choice_label' => 'name',
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->addDependent('currentTown', 'currentCountry', function (DependentField $field, null|Country $country) use ($user): void {
                $field->add(EntityType::class, [
                    'class' => Town::class,
                    'placeholder' => 'city',
                    'data' => $user->getCurrentTown(),
                    'choices' => $country?->getTowns(),
                    'choice_label' => fn (Town $town): string => ucfirst($town->getName()),
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ]);
            })
            ->add('originCountry', EntityType::class, [
                'mapped' => false,
                'required' => false,
                'class' => Country::class,
                'data' => $user->getOriginTown()?->getCountry(),
                'choice_label' => 'name',
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->addDependent('originTown', 'originCountry', function (DependentField $field, null|Country $country) use ($user): void {
                $field->add(EntityType::class, [
                    'class' => Town::class,
                    'required' => false,
                    'placeholder' => 'city',
                    'data' => $user->getOriginTown(),
                    'choices' => $country?->getTowns(),
                    'choice_label' => fn (Town $town): string => ucfirst($town->getName()),
                    'row_attr' => [
                        'class' => 'form-floating mb-3',
                    ],
                ]);
            });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
