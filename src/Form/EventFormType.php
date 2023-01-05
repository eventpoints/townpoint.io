<?php

declare(strict_types = 1);

namespace App\Form;

use App\Entity\Event;
use App\Entity\User;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventFormType extends AbstractType
{
    public function __construct(
        private readonly Security $security
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $currentUser = $this->security->getUser();
        if (! $currentUser instanceof User) {
            throw new Exception('Must be type of User');
        }

        $builder
            ->add('title', TextType::class)
            ->add('address', TextType::class, [
                'tom_select_options' => [
                    'create' => true,
                    'createOnBlur' => true,
                    'multiple' => false,
                    'delimiter' => ';',
                ],
                'autocomplete_url' => '/user/addresses',
                'autocomplete' => true,
            ])
            ->add('startAt', DateTimeType::class, [
                'date_widget' => 'single_text',
                'input' => 'datetime_immutable',
            ])
            ->add('endAt', DateTimeType::class, [
                'required' => false,
                'date_widget' => 'single_text',
                'input' => 'datetime_immutable',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
