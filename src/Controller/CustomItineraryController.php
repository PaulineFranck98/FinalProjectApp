<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\Favorite;
use App\Entity\CustomItinerary;
use App\Form\CustomItineraryType;
use App\Form\SearchItineraryType;
use App\Repository\CityRepository;
use App\Repository\PlaceRepository;
use App\Repository\FavoriteRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CustomItineraryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomItineraryController extends AbstractController
{

    // Ajouter la vérification 'isPublic'
    #[Route('/itinerary', name: 'app_itinerary')]
    public function index(CustomItineraryRepository $itineraryRepository): Response
    {
        $itineraries = $itineraryRepository->findAll();
        return $this->render('custom_itinerary/index.html.twig', [
            'itineraries' => $itineraries,
        ]);
    }

    #[Route('/itinerary/new', name: 'new_itinerary')]

    public function new(CustomItinerary $itinerary, Request $request, EntityManagerInterface $entityManager, CityRepository $cityRepository): Response
    {
        $itinerary = new CustomItinerary();

        $form = $this->createForm(CustomItineraryType::class, $itinerary);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {

            $itinerary->setCreationDate(new \DateTime());

            $user = $this->getUser();

            $itinerary->setUser($user);

            $itinerary = $form->getData();

            // dd($itinerary);
            
            $entityManager->persist($itinerary);

            $entityManager->flush();

            return $this->redirectToRoute('show_itinerary', ['id' => $itinerary->getId()]);
        }

        return $this->render('custom_itinerary/new.html.twig', [
            'formAddItinerary' => $form,
            'itineraryId' => $itinerary->getId()
        ]);
    }
    #[Route('/itinerary/{id}/edit', name: 'edit_itinerary')]
    public function edit(CustomItinerary $itinerary, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(CustomItineraryType::class, $itinerary);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            // $itinerary->setCreationDate(new \DateTime());

            $itinerary = $form->getData();
            // dd($itinerary);
            $entityManager->persist($itinerary);

            $entityManager->flush();

            return $this->redirectToRoute('show_itinerary', ['id' => $itinerary->getId()]);
        }

        return $this->render('custom_itinerary/new.html.twig', [
            'formAddItinerary' => $form,
            'edit' => $itinerary->getId()
        ]);
    }

    #[Route('/itinerary/{id}', name: 'show_itinerary')]
    // retrieve the 'customItinerary' corresponding to the id thanks to paramconverter tool
    public function show(CustomItinerary $itinerary, PlaceRepository $placeRepository)  
    {
        $departurePlacesCount = $placeRepository->countPlacesByCityId($itinerary->getDeparture()->getId());
        $arrivalPlacesCount = $placeRepository->countPlacesByCityId($itinerary->getArrival()->getId());
        
        $cities = [];
        $cityPlaces = [];
        foreach($itinerary->getCities() as $city){
            $cities[] = [
                'cityCode' => $city->getCityCode(),
                'cityName' => $city->getCityName(),
                'cityId' => $city->getId(),
                'cityPlacesCount' => $placeRepository->countPlacesByCityId($city->getId())
            ];

            foreach($city->getPlaces() as $cityPlace){
            $cityPlaces[] = [ 
                'placeName' => $cityPlace->getName(),
                'placeAddress' => $cityPlace->getAddress(),
                'placeLat' => $cityPlace->getLatitude(),
                'placeLng' => $cityPlace->getLongitude(),
                'placeCity' => $cityPlace->getCity()->getCityName(),
                'placeType' => $cityPlace->getType()->getName(),
            ];
            }
        
        }

        $itineraryData = [
            'id' => $itinerary->getId(),
            'name' => $itinerary->getName(),
            'departureCode' => $itinerary->getDeparture()->getCityCode(),
            'arrivalCode' => $itinerary->getArrival()->getCityCode(),
            'arrival'=>$itinerary->getArrival()->getCityName(),
            'departure'=>$itinerary->getDeparture()->getCityName(),
            'departureId'=>$itinerary->getDeparture()->getId(),
            'arrivalId'=>$itinerary->getArrival()->getId(),
            'cities' => $cities,
            'cityPlaces' => $cityPlaces,
            'departurePlacesCount' => $departurePlacesCount,
            'arrivalPlacesCount' => $arrivalPlacesCount,
        ];

        $cityPlacesCount = $placeRepository->countPlacesByCityId($city->getId());
        //I then pass the retrieved 'post' object to the 'show.html.twig' view in the 'post' folder
        return $this->render('custom_itinerary/show.html.twig', [
            'itinerary' => $itinerary,
            'itineraryData' => json_encode($itineraryData),
            'itineraryDatas' => $itineraryData,
            'cityPlacesCount' => $cityPlacesCount,
            'departurePlacesCount' => $departurePlacesCount,
            'arrivalPlacesCount' => $arrivalPlacesCount,
        ]);

    }


    #[Route('/favorite/{id}', name: 'toggle_favorite', methods:['POST'])]
    public function toggleFavorite(EntityManagerInterface $entityManager, FavoriteRepository $favoriteRepository, CustomItinerary $itinerary) : JsonResponse
    {
        
        // dd($itinerary);

        $favorite = $favoriteRepository->findOneBy([
            'customItinerary' => $itinerary,
            'user' => $this->getUser()
        ]);

        if($favorite){
            $entityManager->remove($favorite);
            $entityManager->flush();

            return new JsonResponse(['status' => 'removed']);
        } else{
            $favorite = new Favorite();
            $favorite->setUser($this->getUser());
            $favorite->setCustomItinerary($itinerary);
            $entityManager->persist($favorite);
            $entityManager->flush();
            
            return new JsonResponse(['status' => 'added']);
        }

    }

    #[Route('/itineraries', name: 'search_itinerary')]    
    public function searchItineraries(Request $request, CustomItineraryRepository $itineraryRepository): Response
    {
        $form = $this->createForm(SearchItineraryType::class);
        $form->handleRequest($request);

        if($form->isSubmitted()&& $form->isValid()){
            $data = $form->getData();
            $departure = $data['departure'];
            $arrival = $data['arrival'];
            $duration = $data['duration'];

            $itineraries = $itineraryRepository->search($departure, $arrival, $duration);
        } else {
            $itineraries = $itineraryRepository->findBy([],['isPublic' => true]);
            // $itineraries = $itineraryRepository->findAll();
        }

        return $this->render('custom_itinerary/search.html.twig', [
            'searchForm' => $form,
            'itineraries' => $itineraries,
        ]);
    }

    

}
