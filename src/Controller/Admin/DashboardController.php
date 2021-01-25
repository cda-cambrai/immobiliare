<?php

namespace App\Controller\Admin;

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

        return $this->render('admin/dashboard.html.twig', [
            'userCount' => $userRepository->count([]),
            'realEstateCount' => 98,
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
    }
}
