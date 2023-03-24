<?php

namespace App\Form\Type;

use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MonthYearType extends AbstractType implements DataTransformerInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addModelTransformer($this);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'attr' => [
                'placeholder' => 'MM/YYYY',
            ],
            'invalid_message' => 'Invalid month or year',
        ]);
    }

    public function getParent()
    {
        return TextType::class;
    }

    public function transform($value)
    {
        if ($value instanceof DateTimeInterface) {
            return $value->format('m/Y');
        }

        return null;
    }

    public function reverseTransform($value)
    {
        if (!$value) {
            return null;
        }

        return DateTimeImmutable::createFromFormat('m/Y', $value);
    }
}
