<?php

namespace App\Controller;

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
}
