<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\EditPasswordFormType;
use App\Form\UserModificationType;
use App\Form\ChangePasswordFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route(path: '/user/{id}/edit', name:'edit_user')]
    public function editUser(User $user, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger) : Response
    {
        $userId = $this->getUser()->getId();

        $form = $this->createForm(UserModificationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
             // handle file upload
             /** @var UploadedFile $image */
             $image = $form->get('profilePicture')->getData();
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
                $user->setProfilePicture($newFilename);

            }
            // dd($user);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('show_profile', ['id' =>$userId]);
        }
        return $this->render('security/edit_user.html.twig', [
            'formEditUser' => $form,
            'user' => $user->getId(),
        ]);

    }

    #[Route('/user/{id}/edit_password', name: 'edit_password')]
    public function editPassword(Request $request, UserPasswordHasherInterface $userPasswordHasher, Security $security, EntityManagerInterface $entityManager): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(EditPasswordFormType::class, $user);

        $form->handleRequest($request);
       
        if ($form->isSubmitted() && $form->isValid()) {
      
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            // dd($user);

            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('show_profile', ['id' => $user->getId()]);
        }

        return $this->render('security/edit_password.html.twig', [
            'formEditPassword' => $form,
            'user'=> $user->getId(),
        ]);
    }

    


    #[Route(path: '/user/profile', name: 'show_profile')]
    public function showProfile(): Response
    {
        
        return $this->render('security/profile.html.twig');
    }



    #[Route(path: '/user/posts', name: 'show_posts')]
    public function showPosts(): Response
    {
        
        return $this->render('security/posts.html.twig');
      
    }

    #[Route(path: '/user/ratings', name: 'show_ratings')]
    public function showRatings(): Response
    {
        
        return $this->render('security/ratings.html.twig');
    }

    #[Route(path: '/user/itineraries', name: 'show_itineraries')]
    public function showItineraries(): Response
    {
        
        return $this->render('security/itineraries.html.twig');
    }

}
