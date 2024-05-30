<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use App\Form\RegistrationFormType;
use Symfony\Component\Mime\Address;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Contracts\Translation\TranslatorInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private EmailVerifier $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);
        
        // Je vérifie si le formulaire a été soumis et si les données sont valides
        if ($form->isSubmitted() && $form->isValid()) {
            // Je précise que $profilePictureFile sera une instance de UploadedFile
             /** @var UploadedFile $profilePictureFile */
            // Je récupère les données du champ 'profilePicture' de mon formulaire
            $profilePictureFile = $form->get('profilePicture')->getData();
            // Je vérifie si un fichier a bien été téléchargé
            if($profilePictureFile){
                // J'extrais le nom du fichier original sans l'extension, et je le stocke dans la variable 'originalFilename'
                $originalFilename = pathinfo($profilePictureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // Je rends la chaîne de caractères sûre en enlevant les espaces et caractères spéciaux avec la fonction slug()
                $safeFilename = $slugger->slug($originalFilename);
                // Je génère un nom de fichier unique en ajoutant un identifiant unique
                $newFilename = $safeFilename.'-'.uniqid().'.'.$profilePictureFile->guessExtension();

                try{
                    // Je déplace le fichier vers le dossier où les images téléchargées sont stockées
                    $profilePictureFile->move(
                        // Je récupère le chemin du dossier de téléchargements
                        $this->getParameter('uploads_directory'),
                        // Nouveau nom sous lequel le fichier sera enregitré
                        $newFilename
                    );
                // J'intercepte et gère l'exception en cas d'erreur lors du téléchargement
                } catch(FileException $e){
                    // J'affiche une message d'erreur et stoppe le script 
                    dd('Impossible de déplacer l\'image téléchargée vers le dossier');
                }
                // J'attribue à la propriété 'profilePicture' de l'utilisateur le nouveau nom de fichier    
                $user->setProfilePicture($newFilename);
            }

            // Je définis la valeur de la propriété 'plainPassword' de l'entité User
            $user->setPassword(
                // hache le mot de passe pour le User donné
                $userPasswordHasher->hashPassword(
                    $user,
                    // Je récupère le mot de passe entré dans le champ 'plainPassword' du formulaire
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('admin@demomailtrap.com', 'Admin'))
                    ->to($user->getEmail())
                    ->subject('Please Confirm your Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig')
            );

            // do anything else you need here, like send an email

            // return $security->login($user, AppCustomAuthenticator::class, 'main');
            return $this->redirectToRoute('app_home');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/email', name: 'app_verify_email')]
    // public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    public function verifyUserEmail(Request $request, TranslatorInterface $translator): Response
    {
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');
        // $user = $this->getUser();
        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $this->getUser());
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $translator->trans($exception->getReason(), [], 'VerifyEmailBundle'));

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Your email address has been verified.');

        return $this->redirectToRoute('app_register');
    }
}



