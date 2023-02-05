<?php

declare(strict_types = 1);

namespace App\Form\Filter;

use App\DataTransferObjects\MarketItemFilterDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CurrencyType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class MarketItemFilterForm extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->setMethod(Request::METHOD_GET)
            ->add('title', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('minPrice', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('maxPrice', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('currency', CurrencyType::class, [
                'required' => false,
                'autocomplete' => true,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('condition', ChoiceType::class, [
                'autocomplete' => true,
                'required' => false,
                'choices' => [
                    $this->translator->trans('new') => 'new',
                    $this->translator->trans('new-other') => 'new-other',
                    $this->translator->trans('used') => 'used',
                    $this->translator->trans('seller-refurbished') => 'used',
                    $this->translator->trans('spare-parts-or-not-working') => 'spare-parts-or-not-working',
                ],
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => MarketItemFilterDto::class,
        ]);
    }
}
