<?php

namespace App\Controller\Admin;

use App\Entity\Project;
use App\Enum\DataStatut;
use App\Enum\ProjectObjective;
use App\Form\Type\TrophyRoadFormType;
use App\Form\Type\ProjectTechnologiesFormType;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use EasyCorp\Bundle\EasyAdminBundle\Field\FormField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\NumberField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\UrlField;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\RequestStack;

class ProjectCrudController extends AbstractCrudController
{
    private RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public static function getEntityFqcn(): string
    {
        return Project::class;
    }

    public function configureActions(Actions $actions): Actions
    {
        return $actions->add(Crud::PAGE_INDEX, Action::DETAIL);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            FormField::addTab('Informations générales'),
            FormField::addColumn(8),
            FormField::addFieldset('Informations du projet'),
            TextField::new('title', 'Titre')->setFormTypeOptions(['required' => true]),
            TextField::new('shortDescription', 'Courte description')->hideOnIndex(),
            TextEditorField::new('description', 'Description')->hideOnIndex(),
            NumberField::new('priority', 'Priorité')->setFormTypeOptions(['required' => true]),
            DateField::new('date', 'Date du projet'),
            
            FormField::addColumn(4),
            FormField::addFieldset('Statut et objectif'),
            ChoiceField::new('statut', 'Statut')
                ->setChoices(array_combine(
                    array_map(fn($e) => $e->name, DataStatut::cases()),
                    DataStatut::cases()
                )),
            ChoiceField::new('objective', 'Objectif')
                ->setChoices(array_combine(
                    array_map(fn($e) => $e->name, ProjectObjective::cases()),
                    ProjectObjective::cases()
                )),

            FormField::addFieldset('Tags'),
            AssociationField::new('tags', 'Tags')
                ->setCrudController(TagCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->hideOnIndex(),

            FormField::addTab('Illustrations'),
            FormField::addColumn(6),
            FormField::addFieldset('Illustration carte'),
            ImageField::new('illustrationCardName', 'Aperçu')
                ->setBasePath('/image/project/card')
                ->hideOnForm(),
            Field::new('illustrationCardFile', 'Fichier')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\FileType::class)
                ->onlyOnForms(),
            Field::new('deleteIllustrationCard', 'Supprimer l\'illustration')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\CheckboxType::class)
                ->setFormTypeOptions(['mapped' => false])
                ->onlyOnForms(),

            FormField::addColumn(6),
            FormField::addFieldset('Titre designé'),
            ImageField::new('illustrationTitleName', 'Aperçu')
                ->setBasePath('/image/project/title')
                ->hideOnForm(),
            Field::new('illustrationTitleFile', 'Fichier')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\FileType::class)
                ->onlyOnForms(),
            Field::new('deleteIllustrationTitle', 'Supprimer le titre')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\CheckboxType::class)
                ->setFormTypeOptions(['mapped' => false])
                ->onlyOnForms(),

            FormField::addColumn(6),
            FormField::addFieldset('Arrière plan'),
            ImageField::new('illustrationBackgroundName', 'Aperçu')
                ->setBasePath('/image/project/background')
                ->hideOnForm(),
            Field::new('illustrationBackgroundFile', 'Fichier')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\FileType::class)
                ->onlyOnForms(),
            Field::new('deleteIllustrationBackground', 'Supprimer l\'arrière-plan')
                ->setFormType(\Symfony\Component\Form\Extension\Core\Type\CheckboxType::class)
                ->setFormTypeOptions(['mapped' => false])
                ->onlyOnForms(),

            FormField::addTab('Relations'),
            FormField::addColumn(6),
            FormField::addFieldset('Entités liées'),
            AssociationField::new('society', 'Société')
                ->setCrudController(SocietyCrudController::class)
                ->renderAsNativeWidget(),
            AssociationField::new('school', 'École')
                ->setCrudController(SchoolCrudController::class),
            
            FormField::addColumn(6),
            FormField::addFieldset('Liens externes'),
            UrlField::new('web', 'Lien web'),
            UrlField::new('github', 'Lien GitHub'),

            
            FormField::addColumn(6),
            FormField::addFieldset('Collaborateurs'),
            AssociationField::new('collaborators', 'Collaborateurs')
                ->setCrudController(CollaboratorCrudController::class)
                ->setFormTypeOptions(['by_reference' => false])
                ->hideOnIndex(),

            FormField::addTab('Technologies'),
            FormField::addColumn(12),
            CollectionField::new('projectTechnologies', 'Technologies')
                ->setEntryType(ProjectTechnologiesFormType::class)
                ->setFormTypeOption('by_reference', false)
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex(true)
                ->hideOnIndex(),

            FormField::addTab('Routes des trophées'),
            FormField::addColumn(12),
            CollectionField::new('trophyRoads', 'Routes des trophées')
                ->setEntryType(TrophyRoadFormType::class)
                ->setFormTypeOption('by_reference', false)
                ->allowAdd()
                ->allowDelete()
                ->setEntryIsComplex(true)
                ->hideOnIndex(),

            FormField::addTab('Métadonnées')->onlyOnDetail(),
            DateTimeField::new('createdAt', 'Créé le')->onlyOnDetail(),
            DateTimeField::new('updatedAt', 'Mis à jour le')->onlyOnDetail(),
        ];
    }

    public function updateEntity(EntityManagerInterface $em, $entity): void
    {
        $request = $this->requestStack->getCurrentRequest();
        $data = $request->request->all('Project');

        if (!empty($data['deleteIllustrationCard'])) {
            $entity->setIllustrationCardName(null);
            $entity->setIllustrationCardFile(null);
        }

        if (!empty($data['deleteIllustrationBackground'])) {
            $entity->setIllustrationBackgroundName(null);
            $entity->setIllustrationBackgroundFile(null);
        }

        if (!empty($data['deleteIllustrationTitle'])) {
            $entity->setIllustrationTitleName(null);
            $entity->setIllustrationTitleFile(null);
        }

        $entity->setUpdatedAt(new \DateTimeImmutable());

        parent::updateEntity($em, $entity);
    }
}