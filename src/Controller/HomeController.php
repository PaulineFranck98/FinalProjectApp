<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Form\HomeType;
use App\Entity\Companion;
use App\Repository\CompanionRepository;
use App\Repository\CustomItineraryRepository;
use App\Repository\PlaceRepository;
use App\Repository\PostRepository;
use App\Repository\ThemeRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    #[Route('/home', name: 'app_home')]
    public function index(Request $request, PostRepository $postRepository,ThemeRepository $themeRepository, CompanionRepository $companionRepository, CustomItineraryRepository $itineraryRepository): Response
    {

        $themes = $themeRepository->findAll();
        $companions = $companionRepository->findAll();


        
        $lastPosts = $postRepository->findBy([], ["creationDate" => "DESC"], 3);
        $lastItineraries = $itineraryRepository->findLastPublicItineraries();


        return $this->render('home/index.html.twig', [
            'themes'=> $themes,
            'companions'=> $companions,
            'lastPosts' => $lastPosts,
            'lastItineraries'=> $lastItineraries
        ]);
    }

    
    #[Route('/search', name: 'app_search')]
    public function searchByThemesAndCompanions(Request $request, PlaceRepository $placeRepository,ThemeRepository $themeRepository, CompanionRepository $companionRepository, CustomItineraryRepository $itineraryRepository): Response
    {
         
        if( $request->isMethod('POST'))
        {
            $theme = $request->request->get('themes');

            $companion = $request->request->get('companions');
            // dd($theme, $companion);

            if($theme && $companion){
                $places = $placeRepository->findByThemeAndCompanion($theme, $companion);
                // dd($places);
                $data= [];
                foreach($places as $place)
                {
                    $companions = [];
                    foreach( $place->getCompanions() as $companion){
                        $companions[] = $companion->getName();
                    }

                    $themes = [];
                    foreach($place->getThemes() as $theme){
                        $themes[] = $theme->getName();
                    }

                    $images = [];
                    foreach($place->getImages() as $image){
                        $images[] = $image->getName();
                    }

                    $ratings = [];
                    foreach($place->getRatings() as $rating){
                        $ratings[] = $rating->getRating();
                    }

                    $averageRating = $placeRepository->getAverageRating($place->getId());
                    // dd($images);
                    // dd($averageRating);
                    $data[] = [
                        'id' => $place->getId(),
                        'name' => $place->getName(),
                        'address' => $place->getAddress(),
                        'city' => $place->getCity(),
                        'zipcode' => $place->getZipcode(),
                        'latitude' => $place->getLatitude(),
                        'longitude' => $place->getLongitude(),
                        'type' => $place->getType()->getName(),
                        'averageRating' => $averageRating,
                        'ratings' => $ratings,
                        'companions' => $companions,
                        'themes' => $themes,
                        'images' => $images,
                    ];
                    // dd($data);
                }

                // Je stocke les données en sessions
                // $request->getSession()->set('placesData', $data);

                // Je redirige vers la page de la map
                // return $this->redirectToRoute('app_map');
                return $this->render('map/index.html.twig', [
                    'placesData' => json_encode($data),
                    'places' => $data
                ]);
            }
        }
        
        // $lastItineraries = $itineraryRepository->findLastPublicItineraries();
        return $this->render('home/index.html.twig');

    }
}
