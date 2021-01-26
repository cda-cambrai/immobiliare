<?php

namespace App\Controller;

use App\Entity\RealEstate;
use App\Form\RealEstateType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class RealEstateController extends AbstractController
{
    /**
     * @Route("/tous-les-biens", name="real_estate_list")
     *
     * La page qui affiche la liste des biens.
     */
    public function index(Request $request): Response
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
        //$properties = $repository->findAll();

        // On récupère la surface dans l'url ou 0 si elle n'existe pas
        $properties = $repository->findAllWithFilters(
            $request->get('surface', 0),
            $request->get('budget', 9999999999999),
            $request->get('size')
        );

        return $this->render('real_estate/index.html.twig', [
            'sizes' => $sizes,
            'properties' => $properties,
        ]);
    }

    /**
     * @Route("/nos-biens/{slug}_{id}", name="real_estate_show", requirements={"slug"="[a-z0-9\-]*"})
     *
     * La page qui affiche un bien en détail.
     */
    public function show(Request $request, RealEstate $property)
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
    public function create(Request $request, SluggerInterface $slugger): Response
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
            // On génére le slug et on fait l'upload avant l'ajout en base
            $slug = $slugger->slug($realEstate->getTitle())->lower(); // Le nom de l'annonce devient le-nom-de-l-annonce
            $realEstate->setSlug($slug);

            // On fait l'upload. Comment récupérer l'image ?
            // Equivalent du $_FILES['image']
            /** @var UploadedFile $image */
            $image = $form->get('image')->getData(); // On récupère la valeur du champ
            if ($image) { // Si on upload une image dans l'annnonce
                $fileName = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter('upload_directory'), $fileName);
                $realEstate->setImage($fileName);
            } else {
                // On mets une image par défaut si on upload pas
                $realEstate->setImage('default.png');
            }
            // dd($image); // dump & die

            // On va lier l'annonce à l'utilisateur qui est connecté
            $realEstate->setOwner($this->getUser());

            // Je dois ajouter l'objet dans la BDD
            $entityManager = $this->getDoctrine()->getManager();
            // Je dois mettre l'objet "en attente"
            $entityManager->persist($realEstate);
            // Exécuter la requête
            $entityManager->flush();

            // Faire une redirection après l'ajout et affiche
            // un message de succès
            $this->addFlash('success', 'Votre annonce '.$realEstate->getId().' a bien été ajoutée');

            /*+
                Le tableau des messages ressemble à cela
                [
                    'success' => ['A', 'B', 'C'],
                    'danger' => ['D', 'E'],
                ]
            */

            // Faire la redirection vers la liste des annonces et afficher les messages flashs sur le html
            return $this->redirectToRoute('real_estate_list');
        }

        return $this->render('real_estate/create.html.twig', [
            // Permet d'afficher le formulaire
            'realEstateForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/nos-biens/modifier/{id}", name="real_estate_edit")
     */
    public function edit(Request $request, RealEstate $realEstate)
    {
        // On doit vérifier que l'utilisateur connecté a bien le droit de modifier
        // l'annonce
        if ($this->getUser() !== $realEstate->getOwner()) {
            throw $this->createAccessDeniedException(); // Renvoie une 403
        }

        $form = $this->createForm(RealEstateType::class, $realEstate);

        // Faire le traitement du formulaire...
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // ATTENTION si on change le slug aux histoires de redirections...

            // Upload
            $image = $form->get('image')->getData(); // On récupère la valeur du champ
            if ($image) { // Si on upload une image dans l'annnonce
                // On doit vérifier si une ancienne image est présente pour la supprimer
                // On fera attention de ne pas supprimer default.jpg et les fixtures
                // On est donc sûr de supprimer uniquement les images des utilisateurs
                $defaultImages = ['default.png', 'fixtures/1.jpg', 'fixtures/2.jpg', 'fixtures/3.jpg', 'fixtures/4.jpg', 'fixtures/5.png'];
                if ($realEstate->getImage() && !in_array($realEstate->getImage(), $defaultImages)) {
                    // FileSystem permet de manipuler les fichiers
                    $fs = new Filesystem();
                    // On supprime l'ancienne image
                    $fs->remove($this->getParameter('upload_directory').'/'.$realEstate->getImage());
                }

                $fileName = uniqid() . '.' . $image->guessExtension();
                $image->move($this->getParameter('upload_directory'), $fileName);
                $realEstate->setImage($fileName);
            }

            // Pas besoin de faire de persist... Doctrine va détecter
            // automatiquement qu'il doit faire un UPDATE
            $this->getDoctrine()->getManager()->flush();
            $this->addFlash('success', 'L\'annonce a bien été modifiée');

            return $this->redirectToRoute('real_estate_list');
        }

        return $this->render('real_estate/edit.html.twig', [
            'realEstateForm' => $form->createView(),
            'realEstate' => $realEstate,
        ]);
    }

    /**
     * @Route("/nos-biens/supprimer/{id}", name="real_estate_delete")
     */
    public function delete(RealEstate $realEstate)
    {
        if ($this->getUser() !== $realEstate->getOwner()) {
            throw $this->createAccessDeniedException(); // Renvoie une 403
        }

        // Pour supprimer avec Doctrine
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($realEstate);
        $entityManager->flush(); // DELETE FROM

        $this->addFlash('danger', 'L\'annonce a bien été supprimée');

        return $this->redirectToRoute('real_estate_list');
    }
}
