<?php

namespace App\Form\Filter;

use App\DataTransferObjects\EventFilterDto;
use App\Entity\Event;
use App\Entity\User;
use Exception;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EventFilterForm extends AbstractType
{
    public function __construct(
        private readonly Security $security
    )
    {
    }


    /**
     * @throws Exception
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$this->security->getUser() instanceof User) {
            throw new Exception();
        }

        $builder->setMethod(Request::METHOD_GET)
            ->add('title', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('address', TextType::class, [
                'required' => false,
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('startAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ])
            ->add('endAt', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'row_attr' => [
                    'class' => 'form-floating mb-3',
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EventFilterDto::class,
        ]);
    }
}
