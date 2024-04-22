<?php

namespace App\Form;

// I import the necessary classes to create the form
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Place;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

// I create a new form class for the Post entity, which extends the AbstractType class
// I extend the AbstractType class to access protected and public methods and properties defined in this class
class PostType extends AbstractType
{
    // I define the method that builds the form fields
    // buildForm() is a Symfony form method that defines form fields using the 'FormBuilderInterface' object
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // A form is composed of fields, each of which are built with the help of a field type
        // I define the type of each field in the form to specify the type of data expected  
        $builder
            // TextType renders a basic input text field
            ->add('title', TextType::class, [
                // I define the 'label' attribute, which will be visible to the user to indicate the expected information
                'label' => 'Titre',
            ])
            // TextareaType renders a textarea HTML element
            ->add('content', TextareaType::class, [
                // I define the 'label' attribute for the 'content' field
                'label' => 'Contenu',
            ])
            // FileType renders an input file field
            ->add('image', FileType::class, [
                // I define the 'label' attribute for the 'image' field
                'label'=> 'Image',
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
                // I set 'required' to 'false' to make it optional so I don't have to re-upload the picture every time the user detail is edited
                'required' => false,
                // unmapped fields can't define their validation using attributes in the associated entity
                // so I use the PHP constraint classes
                'constraints' => [
                    // I create a new instance of the 'File' constraint, passing it an array of options
                    new File([
                        // I set the maximum size of the downloaded file
                        'maxSize' => '1024k', //1MB
                        // I define the MIME types allowed for the downloaded file
                        'mimeTypes' => [
                            // only JPEG, PNG and WEBP files are allowed
                            'image/jpeg',
                            'image/png', 
                            'image/webp',
                        ],
                        // error message displayed if the MIME type of the downloaded file is not authorized
                        'mimeTypesMessage' => 'Please upload a valid profile picture (jpeg / png / webp)',
                    ])
                ],
            ])
            // ->add('creationDate', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('isClosed')
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            ->add('place', EntityType::class, [
                'class' => Place::class,
                'choice_label' => 'name',
            ])
            ->add('valider', SubmitType::class)
        ;
    }

    // I define the method that configures the form options
    // configureOptions() is a Symfony form method that sets the form's default options
    public function configureOptions(OptionsResolver $resolver): void
    {
        // $resolver is an object of type 'OptionsResolver', used to configure and validate form options
        // setDefaults() is a method of the 'OptionsResolver' object that sets the default options for a form
        $resolver->setDefaults([
            // I set the default options for the form, here : the data class associated with the form 
            'data_class' => Post::class,
        ]);
    }
}
