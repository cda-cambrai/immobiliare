<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    /**
     * @Route("/hello", name="hello")
     *
     * /hello est l'url de la page.
     * ATTENTION de commencer par /**
     * ATTENTION, il faut utiliser des doubles quotes ici.
     */
    public function hello(): Response
    {
        $name = 'Symfony';
        // On renvoie toujours un objet Response
        // Ici, on précise la balise body pour avoir la Toolbar de Symfony
        // return new Response('<html><body>Hello Symfony</body></html>');

        // Le second paramètre de render est un tableau avec clé => valeur
        // où clé est le nom de la variable Twig et la valeur, celle de la variable
        return $this->render('welcome/hello.html.twig', [
            'name' => $name,
        ]);
    }

    /**
     * @Route("/", name="homepage")
     */
    public function home(): Response
    {
        return $this->render('welcome/home.html.twig');
    }
}
