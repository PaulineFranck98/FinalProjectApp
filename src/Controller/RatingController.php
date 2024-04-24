<?php

namespace App\Controller;

use App\Entity\Rating;
use App\Form\RatingType;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class RatingController extends AbstractController
{
    #[Route('/rating', name: 'app_rating')]
    public function index(): Response
    {
        return $this->render('rating/index.html.twig');
    }

    #[Route('rating/new/{placeId}', name: 'new_rating')]
    public function new(Rating $rating, Request $request, EntityManagerInterface $entityManager, PlaceRepository $placeRepository, $placeId): Response
    {

        $rating = new Rating();

        $place = $placeRepository->findOneById(['id' => $placeId]);

        $form = $this->createForm(RatingType::class, $rating);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){

            $rating = $form->getData();
            // $ratingValue = $rating->getRating();
            $rating->setRatingDate(new \DateTime());
            $user = $this->getUser();
            $rating->setUser($user);

            $rating->setPlace($place);
            // dd($rating);

            $entityManager->persist($rating);
            $entityManager->flush();

            return $this->redirectToRoute('app_home');

        }
        return $this->render('rating/new.html.twig', [
            'formAddRating' => $form,
            'placeId' => $place->getId(),
        ]);
    }
}
