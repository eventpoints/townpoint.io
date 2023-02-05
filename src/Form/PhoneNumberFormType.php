<?php

declare(strict_types = 1);

namespace App\Form;

use App\Entity\PhoneNumber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class PhoneNumberFormType extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('content', TelType::class, [
                'label' => $this->translator->trans('phone-number'),
                'row_attr' => [
                    'class' => 'form-floating w-75 mb-3',
                ],
            ])
            ->add('countryCode', CountryType::class, [
                'label' => $this->translator->trans('dial-code'),
                'row_attr' => [
                    'class' => 'form-floating w-25 mb-3',
                ],
            ])
            ->add('isDefault', CheckboxType::class, [
                'mapped' => false,
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
                'label' => $this->translator->trans('is-default-phone-number'),
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PhoneNumber::class,
        ]);
    }
}
