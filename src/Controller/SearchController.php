<?php

namespace App\Controller;

use App\Repository\RealEstateRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/api/search/{query}", name="api_search")
     */
    public function index($query = '', RealEstateRepository $repository): Response
    {
        // On va chercher les annonces grâce au Repository
        $realEstates = $repository->search($query);
        // On renvoie du JSON car c'est une API
        return $this->json([
            // 'results' => $realEstates,
            'html' => $this->renderView('real_estate/_real_estate.html.twig', ['properties' => $realEstates]),
        ]);
    }
}
