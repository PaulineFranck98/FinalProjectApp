<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\User;
use App\Entity\Place;
use App\Form\CityType;
use App\Entity\CustomItinerary;
use App\Form\CityAutocompleteField;
use Doctrine\DBAL\Types\BooleanType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class CustomItineraryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Donnez un nom à votre itinéraire',
            ])
            // ->add('creationDate', null, [
            //     'widget' => 'single_text',
            // ])
            // ->add('departure', TextType::class, [
            //     'label' => 'Choissisez une ville de départ',
            //     'attr' => [
            //         'data-type' => 'departure'
            //     ]
            //     ])
            // EntityType is a field that's designed to load options from a Doctrine entity
            ->add('departure', EntityType::class, [
                'label' => 'Choisissez votre ville de départ',
                'placeholder' => 'Sélectionnez une ville',
                // defines the entity class to use, here : the entity Theme
                'class' => City::class,
                // I define the entity property to be used as the label for each choice in the list, here : name
                'choice_label' => 'cityName',
                'choice_value' => 'cityCode',
                
            ])
            // EntityType is a field that's designed to load options from a Doctrine entity
            ->add('arrival', EntityType::class, [
                'placeholder' => 'Sélectionnez une ville',
                // defines the entity class to use, here : the entity Theme
                'class' => City::class,
                // I define the entity property to be used as the label for each choice in the list, here : name
                'choice_label' => 'cityName',
                'choice_value' => 'cityCode',
                'label' => 'Choisissez votre ville d\'arrivée',
            ])
            // ->add('codeDeparture', TextType::class, [
            //     'mapped' => false,
            // ])

            // ->add('arrival', TextType::class, [
            //     'label' => 'Choisissez une ville d\'arrivée',
            //     'attr' => [
            //         'data-type' => 'arrival'
            //     ]
            //     ])
            // ->add('codeArrival', TextType::class, [
            //     'mapped' => false,
            // ])

            ->add('cities', CollectionType::class,[
                'entry_type' => EntityType::class,
                   'entry_options' => [
                        'class' => City::class,
                        'choice_label' => 'cityName',
                        'choice_value' => 'cityCode',
                        'placeholder' => 'Sélectionnez une ville',
                    ],

                'prototype' => true,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                ])
         
            ->add('duration', IntegerType::class, [
                'label' => 'Durée estimée de votre itinéraire',
            ])

            ->add('isPublic', ChoiceType::class, [
                'label' => 'Souhaitez-vous que votre itinéraire soit public ?',
                'choices'  => [
                    'Public' => true,
                    'Privé' => false,
                ],
                'multiple' => false
            ])
            // ->add('cities', CityAutocompleteField::class, [
            //     // 'multiple' => true,
            // ])
            
            ->add('valider', SubmitType::class)
            // ->add('user', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'id',
            // ])
            // ->add('place', EntityType::class, [
            //     'class' => Place::class,
            //     'choice_label' => 'id',
            //     'multiple' => true,
            // ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomItinerary::class,
        ]);
    }
}
