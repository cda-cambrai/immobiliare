<?php

namespace App\Controller\Admin;

use App\Entity\RealEstate;
use App\Entity\Type;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    /**
     * @Route("/easyadmin", name="admin")
     */
    public function index(): Response
    {
        $entityManager = $this->getDoctrine();
        // Renvoie un objet App\Repository\UserRepository
        $userRepository = $entityManager->getRepository(User::class);
        $realEstateRepository = $entityManager->getRepository(RealEstate::class);

        return $this->render('admin/dashboard.html.twig', [
            'userCount' => $userRepository->count([]),
            // On compte uniquement les annonces encore en vente
            'realEstateCount' => $realEstateRepository->count(['sold' => false]),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Immobiliare');
    }

    public function configureMenuItems(): iterable
    {
        yield MenuItem::linktoDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Catégories', 'fas fa-list', Type::class);
        yield MenuItem::linkToCrud('Utilisateurs', 'fa fa-user', User::class);
        yield MenuItem::linkToCrud('Annonces', 'fa fa-building', RealEstate::class);
    }
}
