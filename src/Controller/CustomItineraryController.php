<?php

namespace App\Controller;

use App\Entity\CustomItinerary;
use App\Form\CustomItineraryType;
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
    public function new(CustomItinerary $itinerary, Request $request, EntityManagerInterface $entityManager): Response
    {
        $itinerary = new CustomItinerary();

        $form = $this->createForm(CustomItineraryType::class, $itinerary);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $codeDeparture = $form->get('codeDeparture')->getData();
            $codeArrival = $form->get('codeArrival')->getData();

            $itinerary->setDeparture($codeDeparture);
            $itinerary->setArrival($codeArrival);

            $itinerary->setCreationDate(new \DateTime());

            $user = $this->getUser();

            $itinerary->setUser($user);
            
            // dd($itinerary);
            
            $entityManager->persist($itinerary);

            $entityManager->flush();

            return $this->redirectToRoute('app_itinerary');
        }

        return $this->render('custom_itinerary/new.html.twig', [
            'formAddItinerary' => $form,
        ]);
    }

    #[Route('/itinerary/{id}', name: 'show_itinerary')]
    // retrieve the 'customItinerary' corresponding to the id thanks to paramconverter tool
    public function show(CustomItinerary $itinerary)  
    {
        $itineraryData[] = [
            'id' => $itinerary->getId(),
            'name' => $itinerary->getName(),
            'departure' => $itinerary->getDeparture(),
            'arrival' => $itinerary->getArrival()
        ];
        //I then pass the retrieved 'post' object to the 'show.html.twig' view in the 'post' folder
        return $this->render('custom_itinerary/show.html.twig', [
            'itinerary' => $itinerary,
            'itineraryData' => json_encode($itineraryData)
        ]);

    }
}
