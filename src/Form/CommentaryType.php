<?php

namespace App\Form;

// I import the necessary classes to create the form
use App\Entity\Post;
use App\Entity\User;
use App\Entity\Commentary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

// I create a new form class for the Commentary entity, which extends the AbstractType class
// I extend the AbstractType class to access protected and public methods and properties defined in this class
class CommentaryType extends AbstractType
{
    // I define the method that builds the form fields
    // buildForm() is a Symfony form method that defines form fields using the 'FormBuilderInterface' object
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // A form is composed of fields, each of which are built with the help of a field type
        // I define the type of each field in the form to specify the type of data expected 
        $builder
            // TextareaType renders a textarea HTML element
            ->add('content', TextareaType::class, [
                'label' => false,
            ])
            ->add('valider', SubmitType::class)
            // ->add('creationDate', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('post', EntityType::class, [
            //     'class' => Post::class,
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
            'data_class' => Commentary::class,
        ]);
    }
}
