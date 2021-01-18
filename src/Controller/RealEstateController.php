<?php

namespace App\Controller;

use App\Form\RealEstateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('real_estate/index.html.twig');
    }

    /**
     * @Route("/creer-un-bien", name="real_estate_create")
     */
    public function create(): Response
    {
        // Avec Symfony, on peut créer un formulaire
        // Le formulaire est toujours dans une classe à part
        $form = $this->createForm(RealEstateType::class);

        return $this->render('real_estate/create.html.twig', [
            // Permet d'afficher le formulaire
            'realEstateForm' => $form->createView(),
        ]);
    }
}
