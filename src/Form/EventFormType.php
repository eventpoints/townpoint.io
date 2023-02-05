<?php

declare(strict_types = 1);

namespace App\Form;

use App\Entity\Event\Event;
use App\Entity\User;
use Exception;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
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
            ->add('title', TextType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('address', TextType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('startAt', DateTimeType::class, [
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'YYYY-MM-DD HH:mm',
                'input' => 'datetime_immutable',
                'row_attr' => [
                    'data-controller' => 'date-time-picker',
                    'class' => 'form-floating mb-3',
                ],
                'attr' => [
                    'data-date-time-picker-target' => 'picker',
                ],
                'view_timezone' => $currentUser->getTimezone(),
            ])
            ->add('endAt', DateTimeType::class, [
                'html5' => false,
                'widget' => 'single_text',
                'format' => 'YYYY-MM-DD HH:mm',
                'input' => 'datetime_immutable',
                'row_attr' => [
                    'data-controller' => 'date-time-picker',
                    'class' => 'form-floating mb-3',
                ],
                'attr' => [
                    'data-date-time-picker-target' => 'picker',
                ],
                'view_timezone' => $currentUser->getTimezone(),
                'required' => false,
            ])
            ->add('invitations', EntityType::class, [
                'mapped' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
                'class' => User::class,
                'choice_label' => 'handle',
                'autocomplete' => true,
                'multiple' => true,
            ])->add('isTicketed', CheckboxType::class, [
                'label' => 'Generate QR Tickets',
                'help' => 'automatically create QR tickets for event participants',
                'label_attr' => [
                    'class' => 'checkbox-switch',
                ],
                'required' => false,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
