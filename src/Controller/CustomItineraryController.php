<?php

namespace App\Controller;

use App\Entity\City;
use App\Entity\CustomItinerary;
use App\Form\CustomItineraryType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CustomItineraryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CustomItineraryController extends AbstractController
{
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
            // $intermediateCities = $form->get('cities')->getData();
            $codeDeparture = $form->get('codeDeparture')->getData();
            $codeArrival = $form->get('codeArrival')->getData();
            $itinerary->setDeparture($codeDeparture);
            $itinerary->setArrival($codeArrival);

            $itinerary->setCreationDate(new \DateTime());

            $user = $this->getUser();

            $itinerary->setUser($user);

            
            // foreach ($intermediateCities as $cityName){
            //     $city = $cityRepository->findOneBy(['cityName' =>  $cityName]);
                
            //     if($city){
            //         $itinerary->addCity($city);
            //     }
    
            // }

            // dd($intermediateCities);
            

            // foreach ($intermediateCities as $cityName) {
            //     // $city = $cityRepository->find($cityId['id']);
            //     $city = $cityRepository->findOneBy(['cityName' =>  $cityName]);
            //     // $city = $cityRepository->findOneBy(['id' => $cityId]);
            //         // dd($city);
            //         $itinerary->addCity($city);
            //         // $cities[] = $city;
            //     }
            

            // Ajouter les villes intermédiaires à l'itinéraire
            // foreach ($cities as $city) {
                // $itinerary->addCity($city);
// }

            // $itinerary = $form->getData();
            // dd($itinerary);
            
            $entityManager->persist($itinerary);

            $entityManager->flush();

            return $this->redirectToRoute('app_itinerary');
        }

        return $this->render('custom_itinerary/new.html.twig', [
            'formAddItinerary' => $form,
            'itineraryId' => $itinerary->getId()
        ]);
    }

    #[Route('/itinerary/{id}', name: 'show_itinerary')]
    // retrieve the 'customItinerary' corresponding to the id thanks to paramconverter tool
    public function show(CustomItinerary $itinerary)  
    {
        $cities = [];
        foreach($itinerary->getCities() as $city){
            $cities[] = $city->getCityCode();
        }
        $itineraryData = [
            'id' => $itinerary->getId(),
            'name' => $itinerary->getName(),
            'departure' => $itinerary->getDeparture(),
            'arrival' => $itinerary->getArrival(),
            'cities' => $cities,
        ];
        //I then pass the retrieved 'post' object to the 'show.html.twig' view in the 'post' folder
        return $this->render('custom_itinerary/show.html.twig', [
            'itinerary' => $itinerary,
            'itineraryData' => json_encode($itineraryData)
        ]);

    }

    // $itineraryData = [
    //     'id' => $itinerary->getId(),
    //     'name' => $itinerary->getName(),
    //     'places' => []
    // ];
    
    // // Récupérez les villes sélectionnées pour cet itinéraire
    // $places = $itinerary->getPlaces();
    
    // // Ajoutez les données de chaque ville au tableau 'places'
    // foreach ($places as $place) {
    //     $itineraryData['places'][] = [
    //         'name' => $place->getName(),
    //         'code' => $place->getCode()
    //     ];
    // }
}
