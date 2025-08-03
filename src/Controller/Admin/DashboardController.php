<?php
namespace App\Controller\Admin;

use App\Entity\{Collaborator, Project, ProjectTechnology, School, Society, Technology, Trophy};
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Dashboard, MenuItem};
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    public function __construct(private AdminUrlGenerator $adminUrlGenerator) {}

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        // Redirige vers le CRUD Project
        $url = $this->adminUrlGenerator->setController(ProjectCrudController::class)->generateUrl();
        return $this->redirect($url);
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Gestion des données');
        yield MenuItem::linkToCrud('Projects', 'fas fa-folder', Project::class);
        yield MenuItem::linkToCrud('Collaborators', 'fas fa-user', Collaborator::class);
        yield MenuItem::linkToCrud('Technologies', 'fas fa-laptop-code', Technology::class);
        yield MenuItem::linkToCrud('Societies', 'fas fa-building', Society::class);
        yield MenuItem::linkToCrud('Schools', 'fas fa-school', School::class);
        yield MenuItem::linkToCrud('Trophies', 'fas fa-trophy', Trophy::class);
        yield MenuItem::linkToCrud('ProjectTechnologies', 'fas fa-laptop-code', ProjectTechnology::class);

        yield MenuItem::section('Import / Export');

        yield MenuItem::section('Gestion des utilisateurs');
        yield MenuItem::linkToUrl('Portfolio', 'fas fa-external-link-alt', 'https://matteo-k.github.io')
            ->setLinkTarget('_blank');
        yield MenuItem::linkToUrl('Github', 'fas fa-code', 'https://github.com/Matteo-K/matteo-k.github.io')
            ->setLinkTarget('_blank');
    }
}
