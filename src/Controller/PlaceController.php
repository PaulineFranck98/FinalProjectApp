<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use App\Service\PictureService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PlaceController extends AbstractController
{
    #[Route('/place', name: 'app_place')]
    public function index(PlaceRepository $placeRepository): Response
    {
        $places = $placeRepository->findAll();
        return $this->render('place/index.html.twig', [
            'places' => $places,
        ]);
    }

    #[Route('/place/new', name:'new_place')]
    public function new(Place $place, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        $place = new Place();

        // On crée le formulaire
        $form = $this->createForm(PlaceType::class, $place);

        // On traite la requête du formulaire
        $form->handleRequest($request);

        // On vérifie si le formulaire est soumis ET valide
        if($form->isSubmitted() && $form->isValid())
        {   
            // On récupère les images : doit faire un get dans le formulaire
            $images = $form->get('images')->getData();
            // dd($images);
            
            // On boucle sur les images
            foreach($images as $image)
            {
                // On définit le dossier de destination
                $folder = 'place';

                // On appelle le service d'ajout 
                // On récupère le nom du fichier
                $file = $pictureService->add($image, $folder);
                // die; //Pour tester et ne pas aller plus loin dans la génération du formulaire

                // Instanciation de mon entité Image
                $img = new Image();

                // $file est renvoyé par mon service
                $img->setName($file);
                $place->addImage($img);

                // On doit persister l'image dans le lieu
            }
         

            $place = $form->getData();
            // dd($place);
            $entityManager->persist($place);

            $entityManager->flush();

            return $this->redirectToRoute('app_place');

        }

        return $this->render('place/new.html.twig', [
            'formAddPlace' => $form,
        ]);
    }

    #[Route('/place/{id}/edit', name:'edit_place')]
    public function edit(Place $place, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, PictureService $pictureService): Response
    {
    $form = $this->createForm(PlaceType::class, $place);

    $form->handleRequest($request);

    if($form->isSubmitted() && $form->isValid())
    {
        // On récupère les images : doit faire un get dans le formulaire
        $images = $form->get('images')->getData();
        // dd($images);
        
        // On boucle sur les images
        foreach($images as $image)
        {
            // On définit le dossier de destination
            $folder = 'place';

            // On appelle le service d'ajout 
            // On récupère le nom du fichier
            $file = $pictureService->add($image, $folder);
            // die; //Pour tester et ne pas aller plus loin dans la génération du formulaire

            // Instanciation de mon entité Image
            $img = new Image();

            // $file est renvoyé par mon service
            $img->setName($file);
            $place->addImage($img);

            // On doit persister l'image dans le lieu
        }
        $place = $form->getData();
        $entityManager->persist($place);
        $entityManager->flush();

        return $this->redirectToRoute('show_place', ['id' => $place->getId()]);
    }

    return $this->render('place/new.html.twig', [
        'formAddPlace' => $form,
        'edit' => $place->getId(),
        'place' => $place
    ]);
}
    #[Route('/place/{id}/delete', name:'delete_place')]
    public function deletePlace(Place $place, EntityManagerInterface $entityManager, PictureService $pictureService)
    {
        // Je récupère toutes les images associées au lieu
        $images = $place->getImages();

        // Je boucle sur les images et je les supprime une à une
        foreach ($images as $image) {

            // Je récupère le nom de l'image
            $name = $image->getName();

            // Je supprime l'image du dossier grâce à mon pictureService
            $pictureService->delete($name, 'place');

            // Je supprime l'image de la base de données
            $entityManager->remove($image);
        }

        //Je supprime le lieu de la base de données
        $entityManager->remove($place);
        $entityManager->flush();

        return $this->redirectToRoute('app_place');
        
    }


    #[Route('/place/image/{id}/delete', name:'delete_image', methods:['DELETE'])]
    public function deleteImg(Image $image, Request $request, EntityManagerInterface $entityManager, PictureService $pictureService): JsonResponse
    {
        // On récupère le contenu de la requête
        // Le contenu sera en json donc on utilise json_decode
        // True pour faire un tableau associatif
        $data = json_decode($request->getContent(), true);

        // On récupère le token dans $data, et on vérifie s'il est valide
        // Le nom du token doit être le même que celui du data-token
        // On le compare au token qui est dans $data
        // On l'envoie sous le nom '_token'
        if($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])){
            // Le token csrf est valide
            // On récupère le nom de l'image
            $name = $image->getName();

            // On supprime l'image : on encapsule dans un if car va retourner un booléen
            // Si ça fonctionne, on entre dans le if
            if($pictureService->delete($name, 'place')){

                // On supprime l'image de la base de données
                $entityManager->remove($image);
                $entityManager->flush();

                return new JsonResponse(['success' => true], 200);
            }

            // La suppression a échoué
            return new JsonResponse(['error' => 'Erreur de suppression'], 400);

        }

        return new JsonResponse(['error' => 'Token invalide'], 400);
    }



    #[Route('/place/{id}', name: 'show_place')]
    // retrieve the 'place' corresponding to the id thanks to paramconverter tool
    public function show(Place $place, PlaceRepository $placeRepository, $id) : Response {
        //I then pass the retrieved 'place' object to the 'show.html.twig' view in the 'place' folder
        $averageRating = $placeRepository->getAverageRating($id);
        // dd($averageRating);
        return $this->render('place/show.html.twig', [
            'place' => $place,
            'averageRating' => $averageRating
        ]);
    }

}
