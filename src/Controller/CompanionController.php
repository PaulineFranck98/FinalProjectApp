<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CompanionController extends AbstractController
{
    #[Route('/companion', name: 'app_companion')]
    public function index(): Response
    {
        return $this->render('companion/index.html.twig', [
            'controller_name' => 'CompanionController',
        ]);
    }
}
