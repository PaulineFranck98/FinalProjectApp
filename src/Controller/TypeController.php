<?php

namespace App\Controller;

use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TypeController extends AbstractController
{
    #[Route('/type', name: 'app_type')]
    public function index(TypeRepository $typeRepository): Response
    {
        $types = $typeRepository->findAll();
        return $this->render('type/index.html.twig', [
            'types' => $types,
        ]);
    }

    #[Route('/type/new', name: 'new_type')]
    public function new(Type $type, Request $request, EntityManagerInterface $entityManager): Response
    {
        $type = new Type();

        $form = $this->createForm(TypeType::class, $type);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $type = $form->getData();
            // dd($type);
            $entityManager->persist($type);

            $entityManager->flush();

            return $this->redirectToRoute('app_type');
        }

        return $this->render('type/new.html.twig', [
            'formAddType' => $form,
        ]);
    }
    #[Route('/type/{id}/edit', name: 'edit_type')]
    public function edit(Type $type, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(TypeType::class, $type);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $type = $form->getData();
            // dd($type);
            $entityManager->persist($type);

            $entityManager->flush();

            return $this->redirectToRoute('app_type');
        }

        return $this->render('type/new.html.twig', [
            'formAddType' => $form,
            'edit' => $type->getId()
        ]);
    }

    #[Route('/type/{id}/delete', name: 'delete_type')]
    public function delete(Type $type, EntityManagerInterface $entityManager)
    {
        // delete the object 'Type'
        $entityManager->remove($type);
        // apply the SQL query 'DELETE FROM'
        $entityManager->flush();

        return $this->redirectToRoute('app_type');
    }
}   
