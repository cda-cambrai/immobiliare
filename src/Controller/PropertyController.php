<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController
{
    // On partage le tableau dans toute la classe
    private $properties = [
        ['title' => 'Maison avec piscine'],
        ['title' => 'Appartement avec terrasse'],
        ['title' => 'Studio centre ville'],
    ];

    /**
     * @Route("/property/{page}", name="property_list", requirements={"page"="\d+"})
     *
     * Page qui liste les annonces immobilières
     * Avec requirements, on peut vérifier que page est un nombre
     */
    public function index($page = 1): Response
    {
        // Pour démarrer, on va créer un tableau d'annonces
        $properties = $this->properties;

        // Equivalent du var_dump...
        dump($page);
        dump($properties);

        return $this->render('property/index.html.twig', [
            'properties' => $properties,
        ]);
    }

    /**
     * @Route("/property/{slug}", name="property_show")
     *
     * Page qui affiche une annonce avec un paramètre dynamique {slug}
     */
    public function show($slug): Response
    {
        // Ici, on peut vérifier que le slug soit dans notre tableau properties
        if (!in_array($slug, array_column($this->properties, 'title'))) {
            // On renvoie une 404 avec Symfony
            throw $this->createNotFoundException();
        }

        return $this->render('property/show.html.twig', [
            'slug' => $slug,
        ]);
    }
}
