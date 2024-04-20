<?php

declare(strict_types=1);

namespace App\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntitySelectionGroupType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'expanded' => true,
            'multiple' => true,
            'searchable' => true,
        ]);
        $resolver->setAllowedTypes('searchable', 'bool');
    }

    public function getParent(): string
    {
        return EntityType::class;
    }

    public function getBlockPrefix(): string
    {
        return 'entity_selection_group';
    }

    public function buildView(FormView $view, FormInterface $form, array $options): void
    {
        if (isset($options['searchable'])) {
            $view->vars['searchable'] = $options['searchable'];
        }
    }
}
