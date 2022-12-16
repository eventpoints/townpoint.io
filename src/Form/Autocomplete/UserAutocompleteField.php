<?php

namespace App\Form\Autocomplete;

use App\Entity\Address;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\ParentEntityAutocompleteType;

#[AsEntityAutocompleteField]
class UserAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'label' => false,
            'row_attr' => [
                'class' => 'm-0 w-100'
            ],
            'attr' => [
                'class' => 'rounded-pill form-control-lg'
            ],
            'class' => User::class,
            'placeholder' => 'Search',
            'tom_select_options' => [
                'options' => [
                    'render' => [
                        'loading' => '<div class="d-flex justify-content-center"><div class="spinner-border" role="status"><span class="visually-hidden">Loading...</span></div></div>'
                    ],
                ]
            ]
        ]);
    }

    public function getParent(): string
    {
        return ParentEntityAutocompleteType::class;
    }
}
