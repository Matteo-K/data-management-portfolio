<?php

namespace App\Controller\Admin;

use App\Entity\Trophy;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, DateTimeField, DateField, IdField, TextField, TextEditorField, ChoiceField, ImageField, Field, FormField, BooleanField};
use App\Enum\DataStatut;
use App\Enum\TrophyType;

class TrophyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Trophy::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex()
                ->setLabel('ID'),

            ImageField::new('illustrationName')
                ->setBasePath('/image/trophy')
                ->hideOnForm()
                ->setLabel('Illustration au format carte'),

            TextField::new('name')->setFormTypeOptions(['required' => true])
                ->setLabel('Nom'),

            TextEditorField::new('description')
                ->setLabel('Description')
                ->hideOnIndex(),

            BooleanField::new('accomplished')
                ->setLabel('Est t\'accompli ?'),

            // Enums
            ChoiceField::new('statut')
                ->setChoices(array_combine(
                    array_map(fn($e) => $e->name, DataStatut::cases()),
                    DataStatut::cases()
                ))
                ->setLabel('Statut'),
            
            ChoiceField::new('type')
                ->setChoices(array_combine(
                    array_map(fn($e) => $e->name, TrophyType::cases()),
                    TrophyType::cases()
                ))
                ->setLabel('Type'),


            // Uploads (fichiers d'entrée)
            Field::new('illustrationFile')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\FileType::class)
                ->onlyOnForms()
                ->setLabel('Illustration du trophée'),

            // Dates
            DateTimeField::new('createdAt')
                ->onlyOnDetail()
                ->hideOnIndex()
                ->setLabel('Créé le'),

            DateTimeField::new('updatedAt')
                ->onlyOnDetail()
                ->hideOnIndex()
                ->setLabel('Mis à jour le'),

            // Relations
            AssociationField::new('project')
                ->setCrudController(\App\Controller\Admin\ProjectCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->autocomplete()
                ->setLabel('Projet')
                ->hideOnIndex(),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        // Affiche le bouton "Détail" dans la liste
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
