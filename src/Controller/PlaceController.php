<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\CustomItineraryPlaceCity;
use App\Entity\Image;
use App\Entity\Place;
use App\Form\CityType;
use App\Form\CustomItineraryPlaceCityType;
use App\Form\PlaceType;
use App\Service\PictureService;
use App\Repository\CityRepository;
use App\Repository\PostRepository;
use App\Repository\PlaceRepository;
use App\Repository\ThemeRepository;
use App\Repository\CompanionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use App\Repository\CustomItineraryRepository;
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

// Je définis la route pour l'ajout d'un nouveau lieu
#[Route('/place/new', name:'new_place')]
public function new(Place $place, Request $request, EntityManagerInterface $entityManager, PictureService $pictureService, CityRepository $cityRepository): Response
{
    // Je crée un nouvel objet Place que je stocke dans la variable $place
    $place = new Place();

    // Je crée le formulaire pour l'ajout pour l'ajout d'un nouveau lieu en utilisant PlaceType et l'objet Place
    $form = $this->createForm(PlaceType::class, $place);

    // Je traite la requête du formulaire
    $form->handleRequest($request);

    // Je vérifie si le formulaire a été soumis ET s'il est valide
    if($form->isSubmitted() && $form->isValid())
    {   
        // Je récupère les images à partir du formulaire : je dois faire un get dans le formulaire
        $images = $form->get('images')->getData();
        
        // Je boucle sur les images pour les traiter une à une
        foreach($images as $image)
        {
            // Je définis le dossier de destination pour l'image
            $folder = 'place';

            // J'ajoute l'image dans le dossier en utilisant mon PictureService
            $file = $pictureService->add($image, $folder);
            
            // Je crée un nouvel objet Image pour stocker les infos de l'image et je le stocke dans la variable $img
            $img = new Image();

            //Je définis le nom de l'image en utilisant le nom de fichier renvoyé par mon PictureService
            $img->setName($file);

            //J'ajoute l'objet Image à l'objet Place en utilisant la méthode addImage()
            $place->addImage($img);

        }

        // Je récupère le code INSEE de la ville à partir du formulaire et je le stocke dans la variable $cityCode
        $cityCode = $form->get('cityCodeId')->getData();
        
        // Je récupère le nom de la ville à partir du formulaire et je le stocke dans la variable $cityName
        $cityName = $form->get('cityName')->getData();

        // J'utilise 'findBy' pour rechercher la ville avec le même cityCode si elle existe dans ma base de données
        $city = $cityRepository->findOneBy(['cityCode' => $cityCode]);
        
        // Si la ville existe, je l'associe à l'objet Place
        if ($city) {
            // Je définis la ville de l'objet Place 
            $place->setCity($city);
        
        } else {
        // Sinon je crée un nouvel objet City 
            $city = new City();

        // Je définis sa propriété 'cityCode' avec son code INSEE récupéré à partir du formulaire
            $city->setCityCode($cityCode);

        // Je définis sa propriété 'cityName' avec son nom récupéré à partir du formulaire
            $city->setCityName($cityName);
        
        // Je l'associe à l'objet Place
            $place->setCity($city);
        
        // J'ajoute l'objet City à l'EntityManager pour qu'il soit persisté en base de données
            $entityManager->persist($city);
        }
        
    
        // Je récupère les données du formulaire pour l'objet Place
        $place = $form->getData();

        // J'ajoute l'objet Place à l'EntityManager pour qu'il soit persisté en base de données
        $entityManager->persist($place);
        // J'exécute les requêtes pour envoyer les objets en base de données
        $entityManager->flush();
        // Je redirige vers la page d'accueil après la création du nouveau lieu
        return $this->redirectToRoute('app_home');
    

    }
    // Si le formulaire n'a pas été soumis ou qu'il n'est pas valide, j'affiche le formulaire d'ajout dans ma vue twig
    return $this->render('place/new.html.twig', [
        'formAddPlace' => $form,
    ]);
}
    

    #[Route('/place/{id}/edit', name:'edit_place')]
    public function edit(Place $place, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, PictureService $pictureService, CityRepository $cityRepository): Response
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
            
            // Instanciation de mon entité Image
            $img = new Image();

            // $file est renvoyé par mon service
            $img->setName($file);
            $place->addImage($img);

        }

         // Je récupère le code INSEE de la ville et je le stocke dans la variable $cityCode
         $cityCode = $form->get('cityCodeId')->getData();
            
         // Je récupère le nom de la ville et je le stocke dans la variable $cityName
         $cityName = $form->get('cityName')->getData();

         // J'utilise la fonction 'findBy' pour récupérer la ville avec le même cityCode si elle existe
         $city = $cityRepository->findOneBy(['cityCode' => $cityCode]);
       

         // Si la ville n'existe pas dans l'entité city
         if ($city) {
             $place->setCity($city);
         
         } else {
            // Je crée une nouvelle ville
           $city = new City();

            // Je définis son code INSEE
           $city->setCityCode($cityCode);

            // Je définis son nom
           $city->setCityName($cityName);

           $place->setCity($city);

           $entityManager->persist($city);
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
        // Le contenu sera en json donc on utilise json_decode et true pour faire un tableau associatif
        $data = json_decode($request->getContent(), true);

        // On récupère le token dans $data, et on vérifie s'il est valide --> le nom du token doit être le même que celui du data-token
        // On le compare au token qui est dans $data et on l'envoie sous le nom '_token'
        if($this->isCsrfTokenValid('delete' . $image->getId(), $data['_token'])){

            // Le token csrf est valide : on récupère le nom de l'image
            $name = $image->getName();

            // On supprime l'image : on encapsule dans un if car va retourner un booléen
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
    // public function show(Place $place, PlaceRepository $placeRepository, CustomItineraryRepository $itineraryRepository, Security $security, $id, $userId, $placeId) : Response {
    public function show(Request $request, Place $place, PlaceRepository $placeRepository, CustomItineraryRepository $itineraryRepository, Security $security,EntityManagerInterface $entityManager) : Response {
        //I then pass the retrieved 'place' object to the 'show.html.twig' view in the 'place' folder
        $userId = $this->getUser();

        // $count = $itineraryRepository->countItinerariesByPlaceAndUser($place->getId(), $userId); 
        $itinerariesByPlace = $itineraryRepository->getItinerariesByPlaceAndUser($place->getId(), $userId); 
        $itineraries = $itineraryRepository->getItinerariesByPlaceAndUser($place->getId(), $userId); 
        
        $averageRating = $placeRepository->getAverageRating($place->getId());

        // $availableItineraries = $itineraryRepository->findItinerariesByPlaceAndUser($place->getId(), $userId);

        $itineraryPlaceCity = new CustomItineraryPlaceCity();

        $form = $this->createForm(CustomItineraryPlaceCityType::class, null, ['itineraries' => $itineraries]);

        $form->handleRequest($request);

        $selectedItinerary = null;

        if($form->isSubmitted() && $form->isValid())
        {
            $customItinerary = $form->get('customItinerary')->getData();
            $itineraryPlaceCity->setPlace($place);
            $itineraryPlaceCity->setCity($place->getCity());
            $itineraryPlaceCity->setCustomItinerary($customItinerary);


            $entityManager->persist($itineraryPlaceCity);
            $entityManager->flush();
            $this->addFlash('success', 'Le lieu a bien été ajouté à votre itinéraire');

            $itinerariesByPlace = $itineraryRepository->getItinerariesByPlaceAndUser($place->getId(), $userId);
            
            $selectedItinerary = $customItinerary;

            return $this->redirectToRoute('show_place', ['id' => $place->getId()]);


        }
        
        // dd($averageRating);
        return $this->render('place/show.html.twig', [
            'place' => $place,
            'averageRating' => $averageRating,
            // 'count' => $count,
            'itineraryPlaceCity' => $form,
            'itinerariesByPlace' => $itinerariesByPlace,
            'selectedItinerary' => $selectedItinerary,
        ]);
    }


    #[Route('/city/{id}', name: 'show_city')]
    // retrieve the 'post' corresponding to the id thanks to paramconverter tool
    public function showCity(City $city, PlaceRepository $placeRepository, ThemeRepository $themeRepository, CityRepository $cityRepository, CompanionRepository $companionRepository ,Request $request ) : Response {

        // Je récupère les filtres 
        $themeFilters = $request->get('themes');
        $companionFilters = $request->get('companions');
    
       
        // $places = $city->getPlaces($filters);
        $places = $cityRepository->findPlacesByCityId($city->getId(), $themeFilters, $companionFilters);
        
        // dd($places);

        $averageRatings = [];

        foreach ($places as $place) {
            $averageRatings[$place->getId()] = $placeRepository->getAverageRating($place->getId());
        }

        // Je vérifie si j'ai une requête ajax
        if($request->get('ajax')){
            return new JsonResponse([
                'content' => $this->renderView('place/_content.html.twig', [
                    'city' => $city,
                    'places' => $places,
                    'averageRatings' => $averageRatings,
                    
                    ])
                ]);
            }
        // Je récupère tous les thèmes 
        $themes = $themeRepository->findAll();
        $companions = $companionRepository->findAll();

        //I then pass the retrieved 'post' object to the 'show.html.twig' view in the 'post' folder
        return $this->render('place/city.html.twig', [
            'city' => $city,
            'places' => $places,
            'averageRatings' => $averageRatings,
            'themes' => $themes,
            'companions' => $companions,
        ]);
    }

     #[Route('/post/place/{placeId}', name: 'find_posts_place')]
    public function findPostByPlaceId(PostRepository $postRepository, PlaceRepository $placeRepository, $placeId): Response
    {
        $posts = $postRepository->findPostsByPlace($placeId);
        $place = $placeRepository->find($placeId);

        return $this->render('place/posts.html.twig', [
            'posts' => $posts,
            'place' => $place,
        ]);
    }

    
    #[Route('/post/city/{cityId}', name: 'find_posts_city')]
    public function findPostByCityId(PostRepository $postRepository, CityRepository $cityRepository, $cityId, Request $request, EntityManagerInterface $entityManager): Response
    {
        $posts = $postRepository->findPostsByCity($cityId);
        $city = $cityRepository->find($cityId);

        return $this->render('place/cityPosts.html.twig', [
            'posts' => $posts,
            'city' => $city,
        ]);
    }

}







// #[Route('/place/cities_with_places', name: 'cities_with_places')]
// public function citiesWithPlaces(CityRepository $cityRepository, Request $request) : JsonResponse
// {
//     $cities = $cityRepository->findCitiesWithPlaces();
//     return $this->json($cities);
// }


