<?php

declare(strict_types = 1);

namespace App\Form\Autocomplete;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class UserAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'label' => false,
            'row_attr' => [
                'class' => 'm-0 w-100',
            ],
            'attr' => [
                'class' => 'form-control border-secondary',
            ],
            'class' => User::class,
            'placeholder' => 'Search',
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
