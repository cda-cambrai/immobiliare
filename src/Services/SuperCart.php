<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

/**
 * Un service est une classe dont l'objectif
 * est d'être réutilisable. Elle sert aussi à
 * organiser son code.
 */
class SuperCart
{
    private $session;

    /**
     * On peut récupèrer d'autres services dans le constructeur d'un service
     */
    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    /**
     * Permet d'ajouter un item dans le panier
     */
    public function addItem($item)
    {
        // Retourne le tableau de produits présent en session OU un tableau
        // vide
        $products = $this->session->get('products', []);
        $products[$item->getId()] = $item; // L'index évite les doublons
        $this->session->set('products', $products);
    }

    /**
     * Permet de récupèrer les items du panier
     */
    public function getItems()
    {
        return $this->session->get('products', []);
    }

    /**
     * Permet de compter les items dans le panier
     */
    public function count(): int
    {
        return count($this->session->get('products', []));
    }
}
