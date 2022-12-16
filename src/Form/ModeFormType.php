<?php

namespace App\Form;

use App\Entity\Message;
use App\Enum\ModeEnum;
use App\Service\ModeService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModeFormType extends AbstractType
{


    public function __construct(
        private readonly ModeService $modeService
    )
    {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('mode', ChoiceType::class, [
                'choices'=> [
                    ModeEnum::PLAY->value => ModeEnum::PLAY->value,
                    ModeEnum::LEARN->value => ModeEnum::LEARN->value,
                    ModeEnum::WORK->value => ModeEnum::WORK->value,
                ],
                'data' => $this->modeService->getMode(),
                'label' => false,
                'autocomplete' => true
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
