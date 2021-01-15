<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request, $page = 1): Response
    {
        // Pour démarrer, on va créer un tableau d'annonces
        $properties = $this->properties;

        // Equivalent du var_dump...
        //dump($page);
        //dump($properties);

        // On peut récupérer des informations de la requête HTTP
        $surface = $request->query->get('surface'); // Equivaut à $_GET['surface']
        $budget = $request->query->get('budget');
        $size = $request->query->get('size');

        // Il nous manque la BDD pour faire le tri
        dump($surface);
        //dump($request);

        // On prépare un tableau avec la tailles des biens
        // pour générer le select
        $sizes = [
            1 => 'Studio',
            2 => 'T2',
            3 => 'T3',
            4 => 'T4',
            5 => 'T5',
        ];

        return $this->render('property/index.html.twig', [
            'properties' => $properties,
            'sizes' => $sizes,
            // On peut passer surface dans la vue mais pas nécessaire
            // car on l'a dans app.request.get
            'surface' => $surface,
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
            throw $this->createNotFoundException('Annonce non existante');
        }

        return $this->render('property/show.html.twig', [
            'slug' => $slug,
        ]);
    }

    /**
     * @Route("/property.{_format}", name="property_api")
     *
     * _format est un paramètre magique qui permet de formatter la réponse
     */
    public function api(): Response
    {
        return $this->json($this->properties);
        /*return new Response(
            json_encode($this->properties)
        );*/
    }
}
