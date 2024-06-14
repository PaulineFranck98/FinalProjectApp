<?php

namespace App\Form;

// J'importe les classes nécessaires 
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Place;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


// J'étends la classe AbstractType pour accéder aux méthodes et propriétés définies dans cette classe
class PostType extends AbstractType
{
    // buildForm() est une méthode de Symfony qui définit les champs des formulaires en utilisant l'objet FormBuilderInterface
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // Je définis le type de chaque champ pour spécifier le type de donnée attendu
        $builder
            // TextType rend un champ de texte de saisie basique : le type de donnée attendu est une chaîne de caractères
            ->add('title', TextType::class, [
                // Je définis le label du champ 'title' qui sera visible par l'utilisateur
                'label' => 'Titre',
            ])
            // TextareaType rend un élément HTML textarea : le type de donnée attendu est un contenu textuel volumineux
            ->add('content', TextareaType::class, [
                // Je définis le label du champ 'content' qui sera visible par l'utilisateur
                'label' => 'Contenu',
            ])
            // J'ajoute un champ de type 'file' 
            ->add('image', FileType::class, [
                // Je définis son label
                'label'=> 'Image',
                // J'indique que ce champ n'est associé à aucune propriété de l'entité
                'mapped' => false,
                /// Je rends ce champ facultatif pour ne pas avoir à re-télécharger l'image à chaque modification des infos utilisateur
                'required' => false,
                // J'utilise le validateur qui est conçu pour valider des objets par rapport à des contraintes
                'constraints' => [
                    /// J'utilise la contrainte 'File' pour spécifier les contraintes
                    new File([
                        // Je définis la taille max du fichier à 1024 kilo octets, soit 1MB
                        'maxSize' => '1024k', //1MB
                        // Je définis les type MIME accecptés 
                        'mimeTypes' => [
                            // seuls JPEG, PNG et WEBP sont autorisés
                            'image/jpeg',
                            'image/png', 
                            'image/webp',
                        ],
                        // Je définis le message d'erreur affiché si le type MIME n'est pas valide
                        'mimeTypesMessage' => 'Format d\'image invalide. Le format de l\'image doit être de type jpeg, png ou webp)',
                    ])
                ],
            ])
            ->add('valider', SubmitType::class)
        ;

    }

    // configureOptions() est une méthode de formulaire Symfony qui définit les options par défaut du formulaire
    public function configureOptions(OptionsResolver $resolver): void
    {
        // $resolver est un objet de type 'OptionsResolver', utilisé pour configurer et valider les options du formulaire.
        // setDefaults() est une méthode de l'objet 'OptionsResolver' qui définit les options par défaut d'un formulaire.
        $resolver->setDefaults([
            // Je définis les options par défaut du formulaire, ici : la classe associée au formulaire
            'data_class' => Post::class,
        ]);
    }
}
