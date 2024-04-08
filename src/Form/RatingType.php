<?php

namespace App\Form;

// I import the necessary classes to create the form
use App\Entity\User;
use App\Entity\Place;
use App\Entity\Rating;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Validator\Constraints\Range;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

// I create a new form class for the Rating entity, which extends the AbstractType class
// I extend the AbstractType class to access protected and public methods and properties defined in this class
class RatingType extends AbstractType
{   
    // I define the method that builds the form fields
    // buildForm() is a Symfony form method that defines form fields using the 'FormBuilderInterface' object
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // rating is a number between 1 and 5 inclusive
            ->add('rating', IntegerType::class, [
                // 'constraints' is an associative array containing one or more validation constraints
                'constraints' => [
                    // I create a new instance of the 'Range' constraint, passing it an array of options
                    new Range([
                        // minimum value allowed  
                        'min' => 1,
                        // maximum value allowed
                        'max' => 5,
                        // error message displayed if the value is less than the minimum value allowed
                        'minMessage' => 'La note doit être comprise entre 1 et 5',
                        // error message displayed if the value is greater than the maximum value allowed
                        'maxMessage' => 'La note doit être comprise entre 1 et 5',
                    ])
                ]
            ])
            //the rating is mandatory, but the associated comment is optional
            ->add('comment', TextareaType::class, [
                // I set 'required' to 'false' to make it optional
                'required' => false,
            ])
            // ->add('ratingDate', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('place', EntityType::class, [
            //     'class' => Place::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
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
            'data_class' => Rating::class,
        ]);
    }
}
