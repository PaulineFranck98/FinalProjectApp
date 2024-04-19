<?php

namespace App\Form;

use App\Entity\Theme;
use App\Entity\Companion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class HomeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
             // EntityType is a field that's designed to load options from a Doctrine entity
             ->add('themes', EntityType::class, [
                'label' => 'Quel thème choisissez-vous?',
                // defines the entity class to use, here : the entity Theme
                'class' => Theme::class,
                // I define the entity property to be used as the label for each choice in the list, here : name
                'choice_label' => 'name',
                // I set the value to 'false', which means that only one option can be selected 
                'multiple' => false,
                'expanded' => true,
            ])
             // EntityType is a field that's designed to load options from a Doctrine entity
            ->add('companions', EntityType::class, [
                'label' => 'Avec qui partez-vous?',
                // defines the entity class to use, here : the entity Companion
                'class' => Companion::class,
                // I define the entity property to be used as the label for each choice in the list, here : name
                'choice_label' => 'name',
                // I set the value to 'false', which means that only one option can be selected 
                'multiple' => false,
                'expanded' => true,
            ])
          
            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
