<?php

namespace App\Controller\Admin;

use App\Entity\Society;
use App\Enum\DataStatut;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, DateTimeField, IdField, TextField, TextEditorField, ChoiceField, ImageField, Field, UrlField};

class SocietyCrudController extends AbstractCrudController
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getEntityFqcn(): string
    {
        return Society::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex()
                ->setLabel('ID'),

            ImageField::new('logoName')
                ->setBasePath('/image/society')
                ->hideOnForm()
                ->setLabel('Logo'),

            TextField::new('name')->setFormTypeOptions(['required' => true])
                ->setLabel('Nom de l\'entreprise'),

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

            Field::new('deleteLogo')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\CheckboxType::class)
                ->setFormTypeOptions(['mapped' => false])
                ->onlyOnForms()
                ->setLabel('Supprimer le logo'),

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

    public function updateEntity(EntityManagerInterface $em, $entity): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $data = $request->request->all('Society');

        // --- Logo ---
        if (!empty($data['deleteLogo'])) {
            $entity->setLogoName(null);
            $entity->setLogoFile(null);
        }

        $entity->setUpdatedAt(new \DateTimeImmutable());

        parent::updateEntity($em, $entity);
    }
}
