<?php


namespace App\Form\EasyAdminField;

use App\Form\Autocomplete\TagAutocompleteField;
use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;

final class TagSelectorField implements FieldInterface
{
    use FieldTrait;

    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            ->setFormType(TagAutocompleteField::class)
            ->addFormTheme('field/_tag_field.html.twig');
    }
}

