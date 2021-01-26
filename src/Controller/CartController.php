<?php

namespace App\Controller;

use App\Entity\RealEstate;
use App\Services\SuperCart;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CartController extends AbstractController
{
    /**
     * @Route("/cart/add/{id}", name="cart_add")
     */
    public function add(RealEstate $realEstate, SuperCart $superCart): Response
    {
        // Ajouter l'annonce dans la session
        $superCart->addItem($realEstate);

        // Rediriger vers la page de l'annonce
        return $this->redirectToRoute('real_estate_show', [
            'id' => $realEstate->getId(),
            'slug' => $realEstate->getSlug(),
        ]);
    }

    /**
     * @Route("/cart", name="cart_index")
     */
    public function index(SuperCart $superCart)
    {
        return $this->render('cart/index.html.twig', [
            'products' => $superCart->getItems(),
        ]);
    }
}
