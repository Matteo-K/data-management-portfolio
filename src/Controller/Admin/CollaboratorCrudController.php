<?php

namespace App\Controller\Admin;

use App\Entity\Collaborator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, DateTimeField, IdField, TextField, TextEditorField, ChoiceField, ImageField, Field, FormField};
use App\Enum\DataStatut;

class CollaboratorCrudController extends AbstractCrudController
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getEntityFqcn(): string
    {
        return Collaborator::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex()
                ->setLabel('ID'),

            ImageField::new('profileName')
                ->setBasePath('/image/collaborator/profile')
                ->hideOnForm()
                ->setLabel('Photo de profil'),

            ImageField::new('illustrationName')
                ->setBasePath('/image/collaborator/illustration')
                ->onlyOnDetail()
                ->setLabel('Illustration du collaborateur'),

            TextField::new('surname')->setFormTypeOptions(['required' => true])
                ->setLabel('Nom'),

            TextField::new('name')->setFormTypeOptions(['required' => true])
                ->setLabel('Prénom'),

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

            // Uploads (fichiers d'entrée)
            Field::new('profileFile')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\FileType::class)
                ->onlyOnForms()
                ->setLabel('Photo de profil'),

            Field::new('deleteProfile')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\CheckboxType::class)
                ->setFormTypeOptions(['mapped' => false])
                ->onlyOnForms()
                ->setLabel('Supprimer la photo de profil'),

            Field::new('illustrationFile')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\FileType::class)
                ->onlyOnForms()
                ->setLabel('Illustration du collaborateur'),

            Field::new('deleteIllustration')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\CheckboxType::class)
                ->setFormTypeOptions(['mapped' => false])
                ->onlyOnForms()
                ->setLabel('Supprimer l\'illustration du collaborateur'),

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
            AssociationField::new('projects')
                ->setCrudController(\App\Controller\Admin\ProjectCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->autocomplete()
                ->setLabel('Projets'),
        ];
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions
        // Affiche le bouton "Détail" dans la liste
        ->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function updateEntity(EntityManagerInterface $em, $entity): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $data = $request->request->all('Collaborator');

        // --- Photo de profil ---
        if (!empty($data['deleteProfile'])) {
            $entity->setProfileName(null);
            $entity->setProfileFile(null);
        }

        // --- Illustration ---
        if (!empty($data['deleteIllustration'])) {
            $entity->setIllustrationName(null);
            $entity->setIllustrationFile(null);
        }

        $entity->setUpdatedAt(new \DateTimeImmutable());

        parent::updateEntity($em, $entity);
    }
}
