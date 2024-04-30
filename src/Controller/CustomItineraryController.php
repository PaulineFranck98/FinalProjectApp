<?php

namespace App\Controller;

use App\Entity\CustomItinerary;
use App\Form\CustomItineraryType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CustomItineraryRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
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
            $itinerary = $form->getData();
            $itinerary->setCreationDate(new \DateTime());
            $user = $this->getUser();
            $itinerary->setUser($user);
            
            // dd($data['departure']['input']['attributes']['code']);

            dd($itinerary);
            $entityManager->persist($itinerary);

            $entityManager->flush();

            return $this->redirectToRoute('app_itinerary');
        }

        return $this->render('custom_itinerary/new.html.twig', [
            'formAddItinerary' => $form,
        ]);
    }
}
