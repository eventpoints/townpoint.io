<?php

namespace App\Form;

use App\Entity\Auction\Offer;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OfferFormType extends AbstractType
{

    public function __construct(
        private readonly Security $security
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('price', MoneyType::class, [
                'label' => false,
                'attr' => [ 'value' => $options['suggestedOffer']],
                'row_attr' => [
                    'class' => 'form-floating',
                ],
                'currency' => $this->security->getUser()->getCurrency()
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['suggestedOffer']);
        $resolver->setDefaults([
            'data_class' => Offer::class,
        ]);
    }
}
