<?php

declare(strict_types = 1);

namespace App\Form;

use App\Entity\PollAnswer;
use App\Entity\PollOption;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PollAnswerFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('options', EntityType::class, [
                'label' => false,
                'mapped' => false,
                'class' => PollOption::class,
                'choices' => $options['poll']->getPollOptions(),
                'choice_label' => 'content',
                'expanded' => true,
                'label_attr' => [
                    'class' => 'btn btn-light w-100 mb-1',
                ],
                'choice_attr' => function ($choice, $key, $value): array {
                    return [
                        'class' => 'btn-check',
                    ];
                },
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired(['poll']);
        $resolver->setDefaults([
            'data_class' => PollAnswer::class,
        ]);
    }
}
