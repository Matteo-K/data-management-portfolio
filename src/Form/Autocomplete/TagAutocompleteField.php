<?php

namespace App\Form\Autocomplete;

use App\Entity\Tag;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\UX\Autocomplete\Form\AsEntityAutocompleteField;
use Symfony\UX\Autocomplete\Form\BaseEntityAutocompleteType;

#[AsEntityAutocompleteField]
class TagAutocompleteField extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'class' => Tag::class,
            'placeholder' => 'Sélectionner des tags',
            'choice_label' => 'label',
            'multiple' => true,
            'searchable_fields' => ['label'],
            'attr' => [
                'data-controller' => 'autocomplete-created-item',
                'data-autocomplete-created-item-target' => 'select',
                'data-autocomplete-created-item-event-name-value' => 'tag',
                'data-autocomplete-created-item-id-field-value' => 'tagId',
                'data-autocomplete-created-item-display-fields-value' => '["tagLabel"]'
            ],
        ]);
    }

    public function getParent(): string
    {
        return BaseEntityAutocompleteType::class;
    }
}
