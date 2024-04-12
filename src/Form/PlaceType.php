<?php

namespace App\Form;

// I import the necessary classes to create the form
use App\Entity\Type;
use App\Entity\User;
use App\Entity\Place;
use App\Entity\Theme;
use App\Entity\Companion;
use App\Entity\CustomItinerary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

// I create a new form class for the Place entity, which extends the AbstractType class
// I extend the AbstractType class to access protected and public methods and properties defined in this class
class PlaceType extends AbstractType
{
    // I define the method that builds the form fields
    // buildForm() is a Symfony form method that defines form fields using the 'FormBuilderInterface' object
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        // A form is composed of fields, each of which are built with the help of a field type
        // I define the type of each field in the form to specify the type of data expected  
        $builder
            // TextType renders a basic input text field
            ->add('name', TextType::class, [
                // I define the 'label' attribute, which will be visible to the user to indicate the expected information
                'label' => 'Nom',
            ])
            // TextType renders a basic input text field
            ->add('address', TextType::class, [
                // I define the 'label' attribute for the 'address' field
                'label' => 'Adresse',
            ])
            // TextType renders a basic input text field
            ->add('city', TextType::class, [
                // I define the 'label' attribute for the 'city' field
                'label' => 'Ville',
            ])
            // zipcode is a number between 67000 and 68999 inclusive
            ->add('zipcode', NumberType::class, [
                // I define the 'label' attribute for the 'zipcode' field
                'label' => 'Code postal',
                // 'constraints' is an associative array containing one or more validation constraints
                'constraints' => [
                // I create a new instance of the 'Range' constraint, passing it an array of options
                new Range([
                    // minimum value allowed  
                    'min' => 67000,
                    // maximum value allowed
                    'max' => 68999,
                    // error message displayed if the value is not in range
                    'notInRangeMessage' => 'Veuillez saisir un code postal entre {{ min }} et {{ max }}',
                ])
                ]
            ])
            // TextareaType renders a textarea HTML element
            ->add('openingHours', TextareaType::class, [
                // I define the 'label' attribute for the 'openingHours' field
                'label' => 'Horaires d\'ouverture',
            ])
            // UrlType avoids input errors and ensures that links to websites are functional
            ->add('website', UrlType::class, [
                // I define the 'label' attribute for the 'website' field
                'label' => 'Site web',
                // I set 'required' to 'false' to make the website optional if the Place doesn't have one
                'required' => false,
            ])
            // TextType renders a basic input text field
            ->add('phoneNumber', TextType::class, [
                // I define the 'label' attribute for the 'phoneNumber' field
                'label' => 'Numéro de téléphone',
            ])
            // TextareaType renders a textarea HTML element
            ->add('description', TextareaType::class, [
                // I define the 'label' attribute for the 'description' field
                'label' => 'Description',
            ])
            ->add('latitude', NumberType::class, [
                'scale' => 8,
                'attr' => [
                    'inputmode' => 'decimal',
                ]
            ])
            ->add('longitude', NumberType::class,[
                'scale' => 8,
                'attr' => [
                    'inputmode' => 'decimal',
                ]
            ])
            // ->add('isVerified')
             // EntityType is a field that's designed to load options from a Doctrine entity
            ->add('types', EntityType::class, [
                // defines the entity class to use, here : the entity Type
                'class' => Type::class,
                // I define the entity property to be used as the label for each choice in the list, here : name
                'choice_label' => 'name',
                // I set the value to 'true', which means that multiple options can be selected 
                // 'multiple' => true,
            ])
             // EntityType is a field that's designed to load options from a Doctrine entity
            ->add('themes', EntityType::class, [
                // defines the entity class to use, here : the entity Theme
                'class' => Theme::class,
                // I define the entity property to be used as the label for each choice in the list, here : name
                'choice_label' => 'name',
                // I set the value to 'true', which means that multiple options can be selected 
                'multiple' => true,
                'expanded' => true,
            ])
             // EntityType is a field that's designed to load options from a Doctrine entity
            ->add('companions', EntityType::class, [
                // defines the entity class to use, here : the entity Companion
                'class' => Companion::class,
                // I define the entity property to be used as the label for each choice in the list, here : name
                'choice_label' => 'name',
                // I set the value to 'true', which means that multiple options can be selected 
                'multiple' => true,
                'expanded' => true,
            ])
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
            'data_class' => Place::class,
        ]);
    }
}
