<?php

namespace App\Controller;

use App\Entity\Image;
use App\Entity\Place;
use App\Form\PlaceType;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

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
    public function new(Place $place, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $place = new Place();

        $form = $this->createForm(PlaceType::class, $place);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $images = $form->get('images')->getData();

            foreach($images as $image){
                $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                // This is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();

                // move the file to the directory where uploaded pictures are stored
                try{
                    $image->move(
                        $this->getParameter('images_directory'),
                        $newFilename
                    );

                } catch(FileException $e){
                    // handle exception if something happens during file upload 
                    dd('Could not move uploaded picture to directory');
                }
                $img = new Image();
                $img->setName($newFilename);
                $place->addImage($img);
                
            }

            $place = $form->getData();
            dd($place);
            $entityManager->persist($place);

            $entityManager->flush();

            return $this->redirectToRoute('app_place');

        }

        return $this->render('place/new.html.twig', [
            'formAddPlace' => $form,
        ]);
    }

}
