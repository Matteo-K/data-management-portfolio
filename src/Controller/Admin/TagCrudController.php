<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;

class TagCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tag::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Informations générales')->onlyOnDetail(),
              TextField::new('label', 'Libellé'),
              TextEditorField::new('completeLabel', 'Description'),
              AssociationField::new('projects', 'Projets')
                ->setCrudController(ProjectCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->autocomplete()
                ->onlyOnDetail(),
            FormField::addTab('Métadonnées')->onlyOnDetail(),
              DateTimeField::new('createdAt', 'Date de création')->hideOnForm(),
              DateTimeField::new('updatedAt', 'Date de modification')->hideOnForm(),
        ];
    }
}
