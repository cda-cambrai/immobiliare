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
     * Permet de vérifier qu'un produit est déjà dans le panier
     */
    public function hasItem($id)
    {
        $products = $this->session->get('products', []);

        // Renvoie true si le produit est déjà dans le tableau
        return array_key_exists($id, $products);
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

    /**
     * Permet de calculer le total (prix) du panier
     */
    public function total()
    {
        $products = $this->session->get('products', []);
        $total = 0;

        foreach ($products as $product) {
            $total += $product->getPrice();
        }

        return $total;
    }

    /**
     * Permet de retirer un produit du panier
     */
    public function removeItem($id)
    {
        $products = $this->session->get('products', []);

        if ($this->hasItem($id)) {
            unset($products[$id]); // On supprime le produit du panier
        }

        // On mets à jour la session avec le tableau sans le produit supprimé
        $this->session->set('products', $products);
    }

    /**
     * Permet de vider le panier
     */
    public function clear()
    {
        $this->session->set('products', []);
    }
}
