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
            ->add('title', TextType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('address', TextType::class, [
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
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
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Event::class,
        ]);
    }
}
