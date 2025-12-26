<?php

namespace App\Controller\Admin;

use App\Entity\Trophy;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Controller\Admin\TrophyRoadCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\{AssociationField, DateTimeField, DateField, IdField, TextField, TextEditorField, NumberField, ChoiceField, ImageField, Field, FormField, BooleanField};
use App\Enum\DataStatut;
use App\Enum\TrophyType;

class TrophyCrudController extends AbstractCrudController
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

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
                ->setLabel('Est t\' il accompli ?'),

            NumberField::new('priority')->setFormTypeOptions(['required' => true])
                ->setLabel('Priorité'),

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

            Field::new('deleteIllustration')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\CheckboxType::class)
                ->setFormTypeOptions(['mapped' => false])
                ->onlyOnForms()
                ->setLabel('Supprimer l\'illustration'),

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
            AssociationField::new('trophyRoad')
                ->setCrudController(TrophyRoadCrudController::class)
                ->autocomplete()
                ->setLabel('Route des trophées')
                ->hideOnIndex(),

        ];
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('trophyRoad')
        ;
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
        $data = $request->request->all('Trophy');

        // --- Logo ---
        if (!empty($data['deleteIllustration'])) {
            $entity->setIllustrationName(null);
            $entity->setIllustrationFile(null);
        }

        $entity->setUpdatedAt(new \DateTimeImmutable());

        parent::updateEntity($em, $entity);
    }
}
