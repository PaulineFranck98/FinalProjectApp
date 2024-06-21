<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostType;
use App\Repository\PostRepository;
use App\Repository\PlaceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class PostController extends AbstractController
{
    #[Route('/post', name: 'app_post')]
    public function index(PostRepository $postRepository): Response
    {
        $posts = $postRepository->findBy([], ["creationDate" => "DESC"]);

        
        // $posts = $postRepository->findAll();
        
        return $this->render('post/index.html.twig', [
            'posts' => $posts,
        ]);
    }
    
    #[Route('post/new', name: 'new_post')]
    public function new(Post $post, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger) : Response
    {   
        // dd('hello');
        $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // handle file upload
             /** @var UploadedFile $image */
             $image = $form->get('image')->getData();
            //  dd($image);
             if($image){
                 $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                 // This is needed to safely include the file name as part of the URL
                 $safeFilename = $slugger->slug($originalFilename);
                 $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
 
                 // move the file to the directory where uploaded pictures are stored
                 try{
                     $image->move(
                         $this->getParameter('uploads_directory'),
                         $newFilename
                     );
                 } catch(FileException $e){
                     // handle exception if something happens during file upload 
                     dd('Could not move uploaded picture to directory');
                 }
 
                 // updates the 'pictureFilename' property to store the image file name
                 // instead of its content
                 $post->setImage($newFilename);
             }
             
             $post->setCreationDate(new \DateTime());
             $user = $this->getUser();
             $post->setUser($user);
             
             $entityManager->persist($post);
             $entityManager->flush();
 
        
             return $this->redirectToRoute('show_post', ['id' => $post->getId()]);
         }
 
         return $this->render('post/new.html.twig', [
             'formAddPost' => $form,
             'placeSelect' => true,
         ]);
        }


    #[Route('forum/post/new/{placeId}', name: 'new_post_place')]
    public function addPostByPlaceId(Post $post, Request $request, EntityManagerInterface $entityManager, PlaceRepository $placeRepository, $placeId, SluggerInterface $slugger): Response
    {
        $post = new Post();

        $place = $placeRepository->findOneBy(['id'=> $placeId]);

        $post->setPlace($place);

        $form = $this->createForm(PostType::class, $post);
        
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
              // handle file upload
             /** @var UploadedFile $image */
             $image = $form->get('image')->getData();
            //  dd($image);
             if($image){
                 $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                 // This is needed to safely include the file name as part of the URL
                 $safeFilename = $slugger->slug($originalFilename);
                 $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
 
                 // move the file to the directory where uploaded pictures are stored
                 try{
                     $image->move(
                         $this->getParameter('uploads_directory'),
                         $newFilename
                     );
                 } catch(FileException $e){
                     // handle exception if something happens during file upload 
                     dd('Could not move uploaded picture to directory');
                 }
 
                 // updates the 'pictureFilename' property to store the image file name
                 // instead of its content
                 $post->setImage($newFilename);
            }
        
            $post->setCreationDate(new \DateTime());

            $user = $this->getUser();

            $post->setUser($user);
        
            $post = $form->getData();

            // dd($post);
            $entityManager->persist($post);

            $entityManager->flush();

            return $this->redirectToRoute('show_post', ['id' => $post->getId()]);
        }

        return $this->render('post/new.html.twig', [
            'formAddPost' => $form,
            'postId' => $post->getId(),

        ]);
    }
    
    #[Route('/forum/post/{id}/edit', name: 'edit_post')]
    public function edit(Post $post, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger) : Response
    {   
        // dd('hello');
        // $post = new Post();

        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // handle file upload
             /** @var UploadedFile $image */
             $image = $form->get('image')->getData();
            //  dd($image);
             if($image){
                 $originalFilename = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                 // This is needed to safely include the file name as part of the URL
                 $safeFilename = $slugger->slug($originalFilename);
                 $newFilename = $safeFilename.'-'.uniqid().'.'.$image->guessExtension();
 
                 // move the file to the directory where uploaded pictures are stored
                 try{
                     $image->move(
                         $this->getParameter('uploads_directory'),
                         $newFilename
                     );
                 } catch(FileException $e){
                     // handle exception if something happens during file upload 
                     dd('Could not move uploaded picture to directory');
                 }
 
                 // updates the 'pictureFilename' property to store the image file name
                 // instead of its content
                 $post->setImage($newFilename);
             }
             
          
             $entityManager->persist($post);
             $entityManager->flush();
 
        
             return $this->redirectToRoute('app_post');
         }
 
         return $this->render('post/new.html.twig', [
             'formAddPost' => $form,
            //  'placeSelect' => false,
             'edit' => $post->getId()
         ]);
        }


        #[Route('/post/{id}', name: 'show_post')]
        // retrieve the 'post' corresponding to the id thanks to paramconverter tool
        public function show(Post $post) : Response {
            //I then pass the retrieved 'post' object to the 'show.html.twig' view in the 'post' folder
            return $this->render('post/show.html.twig', [
                'post' => $post
            ]);
        }
        
    
}
