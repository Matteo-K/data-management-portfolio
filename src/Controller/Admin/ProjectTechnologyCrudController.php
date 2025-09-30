<?php

namespace App\Controller\Admin;

use App\Entity\ProjectTechnology;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, DateTimeField, IdField, ChoiceField, NumberField, UrlField};
use App\Enum\DataStatut;

class ProjectTechnologyCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return ProjectTechnology::class;
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
            AssociationField::new('technologie')
                ->setCrudController(\App\Controller\Admin\TechnologyCrudController::class)
                ->setFormTypeOptions(['by_reference' => true])
                ->autocomplete()
                ->setLabel('Technologie'),

            NumberField::new('pourcentage_using')
                ->setLabel('Pourcentage d\'utilisation dans le projet'),

            AssociationField::new('project')
                ->setCrudController(\App\Controller\Admin\ProjectCrudController::class)
                ->setFormTypeOptions(['by_reference' => true])
                ->autocomplete()
                ->setLabel('Projet'),

        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        // Affiche le bouton "Détail" dans la liste
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
