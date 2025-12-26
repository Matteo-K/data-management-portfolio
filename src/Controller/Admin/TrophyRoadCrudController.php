<?php

namespace App\Controller\Admin;

use App\Entity\TrophyRoad;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, DateTimeField, DateField, IdField, TextField, TextEditorField, ChoiceField, ImageField, Field, FormField, BooleanField};
use App\Enum\DataStatut;
use App\Enum\RoadType;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;

class TrophyRoadCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return TrophyRoad::class;
    }

    public function configureFields(string $pageName): iterable
    {
        $adminUrlGenerator = $this->container->get(AdminUrlGenerator::class);

        $createTrophyRoadUrl = $adminUrlGenerator
            ->setController(TrophyCrudController::class)
            ->setAction('new')
            ->generateUrl();

        return [
            IdField::new('id')->onlyOnIndex()
                ->setLabel('ID'),

            TextField::new('name')
                ->setLabel('Titre'),

            // Enums
            ChoiceField::new('statut')
                ->setChoices(array_combine(
                    array_map(fn($e) => $e->name, DataStatut::cases()),
                    DataStatut::cases()
                ))
                ->setLabel('Statut'),

            ChoiceField::new('type')
                ->setChoices(array_combine(
                    array_map(fn($e) => $e->name, RoadType::cases()),
                    RoadType::cases()
                ))
                ->setFormTypeOptions([
                    'placeholder' => '— Sélectionnez un type —',
                    'required' => false,
                ])
                ->setLabel('Type'),


            // Relations
            AssociationField::new('project')
                ->setCrudController(\App\Controller\Admin\ProjectCrudController::class)
                ->setFormTypeOptions(['by_reference' => true])
                ->autocomplete()
                ->setLabel('Projets'),

            // Champ visuelle "Nombre de trophées"
            Field::new('trophiesCount', 'Nombre de trophées')
                ->onlyOnIndex()
                ->formatValue(fn ($value, $entity) => $entity->getTrophiesCount()),

            AssociationField::new('trophies')
                ->setCrudController(\App\Controller\Admin\TrophyCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->autocomplete()
                ->setLabel('Trophées')
                ->setHelp('<a href="'.$createTrophyRoadUrl.'" target="_blank" class="btn btn-sm btn-primary mt-2">Ajouter un trophée</a>')
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
