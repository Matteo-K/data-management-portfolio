<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, DateTimeField, DateField, IdField, TextField, TextEditorField, ChoiceField, ImageField, Field, FormField, UrlField};
use App\Enum\DataStatut;
use App\Enum\ProjectObjective;

class ProjectCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex()
                ->setLabel('ID'),

            ImageField::new('illustrationTitleName')
                ->setBasePath('/image/project/title')
                ->onlyOnDetail()
                ->setLabel('Titre designé'),

            ImageField::new('illustrationCardName')
                ->setBasePath('/image/project/card')
                ->hideOnForm()
                ->setLabel('Illustration au format carte'),

            TextField::new('title')->setFormTypeOptions(['required' => true])
                ->setLabel('Titre'),

            TextEditorField::new('description')
                ->setLabel('Description')
                ->hideOnIndex(),

            // Enums
            ChoiceField::new('statut')
                ->setChoices(array_combine(
                    array_map(fn($e) => $e->name, DataStatut::cases()),
                    DataStatut::cases()
                ))
                ->setLabel('Statut'),

            DateField::new('date')
                ->setLabel('Date du projet'),

            UrlField::new('web')
                ->setLabel('Lien web'),

            UrlField::new('github')
                ->setLabel('Lien github'),

            // Uploads (fichiers d'entrée)
            Field::new('illustrationCardFile')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\FileType::class)
                ->onlyOnForms()
                ->setLabel('Illustration au format carte'),

            Field::new('illustrationBackgroundFile')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\FileType::class)
                ->onlyOnForms()
                ->setLabel('Illustration au format arrière plan'),

            Field::new('illustrationTitleFile')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\FileType::class)
                ->onlyOnForms()
                ->setLabel('Titre designé'),

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
            ChoiceField::new('objective')
                ->setChoices(array_combine(
                    array_map(fn($e) => $e->name, ProjectObjective::cases()),
                    ProjectObjective::cases()
                ))
                ->setLabel('Objectif')
                ->hideOnIndex(),

            AssociationField::new('society')
                ->setCrudController(\App\Controller\Admin\SocietyCrudController::class)
                ->renderAsNativeWidget()
                ->setLabel('Société'),

            AssociationField::new('school')
                ->setCrudController(\App\Controller\Admin\SchoolCrudController::class)
                ->autocomplete()
                ->setLabel('École'),

            AssociationField::new('collaborators')
                ->setCrudController(\App\Controller\Admin\CollaboratorCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->autocomplete()
                ->setLabel('Collaborateurs')
                ->hideOnIndex(),

            AssociationField::new('projectTechnologies')
                ->setCrudController(\App\Controller\Admin\ProjectTechnologyCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->autocomplete()
                ->setLabel('Technologies')
                ->hideOnIndex(),

            AssociationField::new('trophyRoads')
                ->setCrudController(\App\Controller\Admin\TrophyRoadCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->autocomplete()
                ->setLabel('Routes des trophées')
                ->hideOnIndex(),

            ImageField::new('illustrationBackgroundName')
                ->setBasePath('/image/project/background')
                ->onlyOnDetail()
                ->setLabel('Illustration au format arrière plan'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }
}
