<?php

namespace App\Controller;

use App\Entity\CustomItinerary;
use App\Repository\CustomItineraryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MapController extends AbstractController
{
    #[Route('/map', name: 'app_map')]
    public function index(Request $request)
    {
        // Je récupère les données des lieux à partir de la sessions
        $placesData = $request->getSession()->get('placesData');

        return $this->render('map/index.html.twig', [
            'placesData' => json_encode($placesData),
            'places' => $placesData
        ]);
    }

    // #[Route('/map/itinerary/{id}', name: 'map_itinerary')]
    // // retrieve the 'customItinerary' corresponding to the id thanks to paramconverter tool
    // public function show(CustomItinerary $itinerary, CustomItineraryRepository $itineraryRepository, $id)
    // {

    //     $itineraryId = $itineraryRepository->find($id);

    //     // $data[] = [
    //     //     'id' => $itinerary->getId(),
    //     //     'departure' => $itinerary->getDeparture(),
    //     //     'arrival' => $itinerary->getArrival()
    //     // ];

    //     // return $this->render('custom_itinerary/show.html.twig', [
    //     //     'id' => $itineraryId,
    //     //     'itinerary' => $itinerary,
    //     //     'data' => json_encode($data)
    //     // ]);
    //     return $this->json([
    //         'id' => $itinerary->getId(),
    //         'departure' => $itinerary->getDeparture(),
    //         'arrival' => $itinerary->getArrival()
    //     ]);
        
        
    // }
}
