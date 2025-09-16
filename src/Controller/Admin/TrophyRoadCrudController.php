<?php

namespace App\Controller\Admin;

use App\Entity\TrophyRoad;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, DateTimeField, DateField, IdField, TextField, TextEditorField, ChoiceField, ImageField, Field, FormField, BooleanField};
use App\Enum\DataStatut;

class TrophyRoadCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TrophyRoad::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex()
                ->setLabel('ID'),

            // Enums
            ChoiceField::new('statut')
                ->setChoices(array_combine(
                    array_map(fn($e) => $e->name, DataStatut::cases()),
                    DataStatut::cases()
                ))
                ->setLabel('Statut'),


            // Relations
            AssociationField::new('project')
                ->setCrudController(\App\Controller\Admin\ProjectCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->autocomplete()
                ->setLabel('Projets'),

            // ➡️ Nouveau champ "Nombre de trophées"
            Field::new('trophiesCount', 'Nombre de trophées')
                ->onlyOnIndex()
                ->formatValue(fn ($value, $entity) => $entity->getTrophiesCount()),

            AssociationField::new('trophies')
                ->setCrudController(\App\Controller\Admin\TrophyCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->autocomplete()
                ->setLabel('Trophées')
                ->hideOnIndex(),

            // Dates
            DateTimeField::new('createdAt')
                ->onlyOnDetail()
                ->hideOnIndex()
                ->setLabel('Créé le'),

            DateTimeField::new('updatedAt')
                ->onlyOnDetail()
                ->hideOnIndex()
                ->setLabel('Mis à jour le'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        // Affiche le bouton "Détail" dans la liste
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
