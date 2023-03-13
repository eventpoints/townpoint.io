<?php

namespace App\Form;

use App\Entity\Image;
use App\Entity\Market\Classified;
use App\Entity\Market\Item;
use App\Service\ImageUploadService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Uid\Uuid;

class ClassifiedFormType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ]
            ])
            ->add('items', CollectionType::class,
                [
                    'label' => false,
                    'entry_type' => MarketItemFormType::class,
                    'entry_options' => ['label' => false],
                    'allow_delete' => true,
                    'allow_add' => true,
                    'by_reference' => false,
                ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Classified::class,
        ]);
    }
}
