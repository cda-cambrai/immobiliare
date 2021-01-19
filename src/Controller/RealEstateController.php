<?php

namespace App\Controller;

use App\Entity\RealEstate;
use App\Form\RealEstateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RealEstateController extends AbstractController
{
    /**
     * @Route("/nos-biens", name="real_estate_list")
     *
     * La page qui affiche la liste des biens.
     */
    public function index(): Response
    {
        $sizes = [
            1 => 'Studio',
            2 => 'T2',
            3 => 'T3',
            4 => 'T4',
            5 => 'T5',
        ];
        // On appelle le dépôt d'une entité (là où sont stockés les entités)
        $repository = $this->getDoctrine()->getRepository(RealEstate::class);
        // Equivaut à un SELECT * FROM real_estate
        $properties = $repository->findAll();

        return $this->render('real_estate/index.html.twig', [
            'sizes' => $sizes,
            'properties' => $properties,
        ]);
    }

    /**
     * @Route("/nos-biens/{id}", name="real_estate_show")
     *
     * La page qui affiche un bien en détail.
     */
    public function show(RealEstate $property)
    {
        // Avec le @ParamConverter, on n'a pas besoin d'écrire le code suivant
        // Il suffit de typer le pararmètre avec l'entité que l'on souhaite
        // récupèrer

        // On récupère la propriété en BDD
        //$property = $this->getDoctrine()->getRepository(RealEstate::class)
        //    ->find($id);

        // Renvoie une 404 si la propriété n'existe pas
        //if (!$property) {
        //    throw $this->createNotFoundException();
        //}

        return $this->render('real_estate/show.html.twig', [
            'property' => $property,
            'title' => $property->getTitle(),
        ]);
    }

    /**
     * @Route("/creer-un-bien", name="real_estate_create")
     */
    public function create(Request $request): Response
    {
        // Avec Symfony, on peut créer un formulaire
        // Le formulaire est toujours dans une classe à part
        // Dans la plupart des cas, on passe une entité à un formulaire
        $realEstate = new RealEstate(); // use App\Entity\RealEstate;
        $form = $this->createForm(RealEstateType::class, $realEstate);

        // Il faut lié le formulaire à la requête (pour récupèrer $_POST)
        $form->handleRequest($request);

        // On doit vérifier que le formulaire est soumis et valide
        if ($form->isSubmitted() && $form->isValid()) {
            // Ici, on va ajouter l'annonce dans la base...
            // $form->getData() permet de récupérer les données d'un formulaire
            // $realEstate est la même chose que $form->getData()
            dump($realEstate);

            // Je dois ajouter l'objet dans la BDD
            $entityManager = $this->getDoctrine()->getManager();
            // Je dois mettre l'objet "en attente"
            $entityManager->persist($realEstate);
            // Exécuter la requête
            $entityManager->flush();
        }

        return $this->render('real_estate/create.html.twig', [
            // Permet d'afficher le formulaire
            'realEstateForm' => $form->createView(),
        ]);
    }
}
