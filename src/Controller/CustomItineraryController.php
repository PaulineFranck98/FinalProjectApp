<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CustomItineraryController extends AbstractController
{
    #[Route('/custom/itinerary', name: 'app_custom_itinerary')]
    public function index(): Response
    {
        return $this->render('custom_itinerary/index.html.twig', [
            'controller_name' => 'CustomItineraryController',
        ]);
    }
}
