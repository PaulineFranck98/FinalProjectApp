<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Form\HomeType;
use App\Entity\Companion;
use App\Repository\PlaceRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, PlaceRepository $placeRepository): Response
    {
        $form = $this->createForm(HomeType::class);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid())
        {
            $theme = $form->get('themes')->getData();
            $companion = $form->get('companions')->getData();

            $places = $placeRepository->findByThemeAndCompanion($theme, $companion);
            // dd($places);
            $data= [];
            foreach($places as $place)
            {
                $data[] = [
                    'id' => $place->getId(),
                    'name' => $place->getName(),
                    'address' => $place->getAddress(),
                    'city' => $place->getCity(),
                    'latitude' => $place->getLatitude(),
                    'longitude' => $place->getLongitude(),
                    'type' => $place->getType()->getName(),
                ];
            }

            // Je stocke les données en sessions
            $request->getSession()->set('placesData', $data);

            // Je redirige vers la page de la map
            return $this->redirectToRoute('app_map');
        }

        return $this->render('home/index.html.twig', [
            'formFindPlace' => $form,
        ]);
    }

  


}
