<?php

namespace App\Controller;

use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
    public function new(Place $place, Request $request, EntityManagerInterface $entityManager): Response
    {
        $place = new Place();

        $form = $this->createForm(PlaceType::class, $place);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
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

}
