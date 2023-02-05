<?php

namespace App\Form;

use App\Entity\Market\Item;
use App\Entity\User;
use App\Exception\ShouldNotHappenException;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class MarketItemFormType extends AbstractType
{

    public function __construct(
        private readonly Security            $security,
        private readonly TranslatorInterface $translator
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $this->security->getUser();

        if (!$user instanceof User) {
            throw new ShouldNotHappenException('user is needed to create a market item');
        }

        $builder
            ->add('images', FileType::class,[
                'mapped' => false,
                'multiple' => true
            ])
            ->add('title', TextType::class,[
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ]
            ])
            ->add('description', TextareaType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ]
            ])
            ->add('price', MoneyType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'currency' => $user->getCurrency()
            ])
            ->add('isAcceptingPriceOffers', CheckboxType::class, [
                'help' => $this->translator->trans('market-item-accepting-price-offers-explainer'),
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
            ])
            ->add('condition', ChoiceType::class, [
                'choices' => [
                    $this->translator->trans('new') => 'new',
                    $this->translator->trans('new-other') => 'new-other',
                    $this->translator->trans('used') => 'used',
                    $this->translator->trans('seller-refurbished') => 'used',
                    $this->translator->trans('spare-parts-or-not-working') => 'spare-parts-or-not-working',
                ],
                'autocomplete' => true
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
