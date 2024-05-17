<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

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

    // #[Route(path: '/user/{id}/edit', name:'edit_user')]
    // public function editUser(User $user, Request $request, )



    #[Route(path: '/profile/{id}', name: 'show_profile')]
    public function showProfile(): Response
    {
        if ($this->getUser()) {
            $id = $this->getUser()->getId();
            
            // dump($id); 
            return $this->render('security/profile.html.twig', ['id' => $id]);
        }

        return $this->redirectToRoute('app_home');
    }

    #[Route(path: '/posts/{id}', name: 'show_posts')]
    public function showPosts(): Response
    {
        if ($this->getUser()) {
            $id = $this->getUser()->getId();
            $posts = $this->getuser()->getPosts();
            // dump($id); 
            return $this->render('security/posts.html.twig', [
                'id' => $id,
                'posts' => $posts,
            ]);
        }

        return $this->redirectToRoute('app_home');
    }

    #[Route(path: '/ratings/{id}', name: 'show_ratings')]
    public function showRatings(): Response
    {
        if ($this->getUser()) {
            $id = $this->getUser()->getId();
            $ratings = $this->getuser()->getRatings();
            // dump($id); 
            return $this->render('security/ratings.html.twig', [
                'id' => $id,
                'ratings' => $ratings,
            ]);
        }

        return $this->redirectToRoute('app_home');
    }

}
