<?php

namespace App\Controller\Admin;

use App\Entity\School;
use App\Enum\DataStatut;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, DateTimeField, IdField, TextField, TextEditorField, ChoiceField, ImageField, Field, UrlField};

class SchoolCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return School::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex()
                ->setLabel('ID'),

            ImageField::new('logoName')
                ->setBasePath('/image/school')
                ->hideOnForm()
                ->setLabel('Logo'),

            TextField::new('name')->setFormTypeOptions(['required' => true])
                ->setLabel('Nom de l\'école'),

            TextEditorField::new('description')
                ->setLabel('Description'),

            UrlField::new('link_web')
                ->setLabel('Lien web'),

            // Enums
            ChoiceField::new('statut')
                ->setChoices(array_combine(
                    array_map(fn($e) => $e->name, DataStatut::cases()),
                    DataStatut::cases()
                ))
                ->setLabel('Statut'),

            // Uploads (fichiers d'entrée)
            Field::new('logoFile')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\FileType::class)
                ->onlyOnForms()
                ->setLabel('Logo'),

            // Dates
            DateTimeField::new('createdAt')
                ->onlyOnDetail()
                ->setLabel('Créé le'),

            DateTimeField::new('updatedAt')
                ->onlyOnDetail()
                ->setLabel('Mis à jour le'),

            // Relations
            AssociationField::new('projects')
                ->setCrudController(\App\Controller\Admin\ProjectCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->autocomplete()
                ->setLabel('Projets')
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
