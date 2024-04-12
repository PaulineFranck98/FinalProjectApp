<?php

namespace App\Controller;

use App\Entity\Companion;
use App\Form\CompanionType;
use App\Repository\CompanionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompanionController extends AbstractController
{
    #[Route('/companion', name: 'app_companion')]
    public function index(CompanionRepository $companionRepository): Response
    {
        $companions = $companionRepository->findAll();
        return $this->render('companion/index.html.twig', [
            'companions' => $companions,
        ]);
    }

    #[Route('/companion/new', name: 'new_companion')]
    public function new(Companion $companion, Request $request, EntityManagerInterface $entityManager): Response
    {
        $companion = new Companion();

        $form = $this->createForm(CompanionType::class, $companion);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $companion = $form->getData();
            // dd($companion);
            $entityManager->persist($companion);

            $entityManager->flush();

            return $this->redirectToRoute('app_companion');
        }

        return $this->render('companion/new.html.twig', [
            'formAddCompanion' => $form,
        ]);
    }
    #[Route('/companion/{id}/edit', name: 'edit_companion')]
    public function edit(Companion $companion, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(CompanionType::class, $companion);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $companion = $form->getData();
            // dd($companion);
            $entityManager->persist($companion);

            $entityManager->flush();

            return $this->redirectToRoute('app_companion');
        }

        return $this->render('companion/new.html.twig', [
            'formAddCompanion' => $form,
            'edit' => $companion->getId()
        ]);
    }

    #[Route('/companion/{id}/delete', name: 'delete_companion')]
    public function delete(Companion $companion, EntityManagerInterface $entityManager)
    {
        // delete the object 'companion'
        $entityManager->remove($companion);
        // apply the SQL query 'DELETE FROM'
        $entityManager->flush();

        return $this->redirectToRoute('app_companion');
    }
}
