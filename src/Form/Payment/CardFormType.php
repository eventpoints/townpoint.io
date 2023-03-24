<?php

namespace App\Form\Payment;

use App\Form\Type\MonthYearType;
use App\Model\Card;
use Payum\Core\Bridge\Symfony\Form\Type\CreditCardExpirationDateType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CardFormType extends AbstractType
{
    public function __construct()
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('holder', TextType::class, ['label' => 'form.credit_card.holder',
            'row_attr' => [
                'class' => 'form-floating mb-3',
            ]
        ])
            ->add('number', TextType::class, ['label' => 'form.credit_card.number',
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ]])
            ->add('securityCode', TextType::class, ['label' => 'form.credit_card.security_code',
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ]])
            ->add(
                'expireAt',
                MonthYearType::class,
            );
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Card::class,
        ]);
    }
}