<?php

namespace App\Controller;

use App\Entity\Theme;
use App\Form\ThemeType;
use App\Repository\ThemeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ThemeController extends AbstractController
{
    #[Route('/theme', name: 'app_theme')]
    public function index(ThemeRepository $themeRepository): Response
    {
        $themes = $themeRepository->findAll();
        return $this->render('theme/index.html.twig', [
            'themes' => $themes,
        ]);
    }

    #[Route('/theme/new', name: 'new_theme')]
    public function new(Theme $theme, Request $request, EntityManagerInterface $entityManager): Response
    {
        $theme = new Theme();

        $form = $this->createForm(ThemeType::class, $theme);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $theme = $form->getData();
            // dd($theme);
            $entityManager->persist($theme);

            $entityManager->flush();

            return $this->redirectToRoute('app_theme');
        }

        return $this->render('theme/new.html.twig', [
            'formAddTheme' => $form,
        ]);
    }
    #[Route('/theme/{id}/edit', name: 'edit_theme')]
    public function edit(Theme $theme, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(ThemeType::class, $theme);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $theme = $form->getData();
            // dd($theme);
            $entityManager->persist($theme);

            $entityManager->flush();

            return $this->redirectToRoute('app_theme');
        }

        return $this->render('theme/new.html.twig', [
            'formAddTheme' => $form,
            'edit' => $theme->getId()
        ]);
    }

    #[Route('/theme/{id}/delete', name: 'delete_theme')]
    public function delete(Theme $theme, EntityManagerInterface $entityManager)
    {
        // delete the object 'theme'
        $entityManager->remove($theme);
        // apply the SQL query 'DELETE FROM'
        $entityManager->flush();

        return $this->redirectToRoute('app_theme');
    }
}
