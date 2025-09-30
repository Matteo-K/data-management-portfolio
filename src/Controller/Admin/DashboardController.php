<?php
namespace App\Controller\Admin;

use App\Entity\{Collaborator, Project, ProjectTechnology, School, Society, Technology, Trophy, TrophyRoad};
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use EasyCorp\Bundle\EasyAdminBundle\Config\{Dashboard, MenuItem};
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminDashboard;

#[AdminDashboard]
class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Portfolio Matteo-K');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::section('Gestion des données');
        yield MenuItem::linkToCrud('Projects', 'fas fa-folder', Project::class);
        yield MenuItem::linkToCrud('Collaborators', 'fas fa-user', Collaborator::class);
        yield MenuItem::linkToCrud('Technologies', 'fas fa-laptop-code', Technology::class)
            ->setDefaultSort(['priority' => 'ASC']);
        yield MenuItem::linkToCrud('Societies', 'fas fa-building', Society::class);
        yield MenuItem::linkToCrud('Schools', 'fas fa-school', School::class);
        yield MenuItem::linkToCrud('Trophies', 'fas fa-trophy', Trophy::class);
        yield MenuItem::linkToCrud('Routes des trophées', 'fas fa-road', TrophyRoad::class);
        yield MenuItem::linkToCrud('ProjectTechnologies', 'fas fa-laptop-code', ProjectTechnology::class);

        yield MenuItem::section('Gestion des données');
        yield MenuItem::linkToRoute('Backup', 'fas fa-file-export', 'data.export');
        yield MenuItem::linkToRoute('Image', 'fas fa-image', 'data.export.files');

        yield MenuItem::section('Liens utiles');
        yield MenuItem::linkToUrl('Portfolio', 'fas fa-user-tie', 'https://matteo-k.github.io')
            ->setLinkTarget('_blank');
        yield MenuItem::linkToUrl('Github Portfolio', 'fas fa-suitcase', 'https://github.com/Matteo-K/matteo-k.github.io')
            ->setLinkTarget('_blank');
        yield MenuItem::linkToUrl('Github Gestion de donnée', 'fas fa-cart-flatbed-suitcase', 'https://github.com/Matteo-K/data-management-portfolio')
            ->setLinkTarget('_blank');
    }
}
