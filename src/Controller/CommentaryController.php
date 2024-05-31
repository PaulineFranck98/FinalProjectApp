<?php

namespace App\Controller;

use App\Entity\Commentary;
use App\Form\CommentaryType;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentaryController extends AbstractController
{
    #[Route('/commentary', name: 'app_commentary')]
    public function index(): Response
    {
        return $this->render('commentary/index.html.twig', [
            'controller_name' => 'CommentaryController',
        ]);
    }

    #[Route('/commentary/new/{postId}', name: 'new_commentary')]
    public function addCommentByPostId(Commentary $commentary, Request $request, EntityManagerInterface $entityManager, PostRepository $postRepository, $postId): Response
    {
        $commentary = new Commentary();

        $post = $postRepository->findOneBy(['id'=> $postId]);

        $form = $this->createForm(CommentaryType::class, $commentary);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $commentary->setCreationDate(new \DateTime());

            $user = $this->getUser();

            $commentary->setUser($user);

            $commentary->setPost($post);
        
            $commentary = $form->getData();

            // dd($commentary);
            $entityManager->persist($commentary);

            $entityManager->flush();

            return $this->redirectToRoute('show_post', ['id' => $postId]);
        }

        return $this->render('commentary/new.html.twig', [
            'formAddCommentary' => $form,
        ]);
    }


    #[Route('/commentary/{id}/edit/{postId}', name: 'edit_commentary')]
    public function editComment(Commentary $commentary, Request $request, EntityManagerInterface $entityManager, PostRepository $postRepository, $postId): Response
    {


        $post = $postRepository->findOneBy(['id'=> $postId]);

        $form = $this->createForm(CommentaryType::class, $commentary);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            
        
            $commentary = $form->getData();

            // dd($commentary);
            $entityManager->persist($commentary);

            $entityManager->flush();

            return $this->redirectToRoute('show_post', ['id' => $postId]);
        }

        return $this->render('commentary/new.html.twig', [
            'formAddCommentary' => $form,
            'edit'=> $commentary->getId(),
            'postId' => $postId
        ]);
    }

    
    #[Route('/commentary/{id}/delete/{postId}', name: 'delete_commentary')]
    public function delete(Commentary $commentary, EntityManagerInterface $entityManager, PostRepository $postRepository, $postId)
    {
        $post = $postRepository->findOneBy(['id'=> $postId]);
        // delete the object 'commentary'
        $entityManager->remove($commentary);
        // apply the SQL query 'DELETE FROM'
        $entityManager->flush();

        return $this->redirectToRoute('show_post', ['id' => $postId]);
    }
}
