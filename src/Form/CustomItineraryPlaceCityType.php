<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\CustomItinerary;
use App\Entity\CustomItineraryPlaceCity;
use App\Entity\Place;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CustomItineraryPlaceCityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $itineraries = $options['itineraries'];
        $builder
     
            ->add('customItinerary', EntityType::class, [
                'class' => CustomItinerary::class,
                'label' => 'A quel itinéraire souhaitez-vous ajouter ce lieu?',
                'choice_label' => 'name',
            ])

            ->add('valider', SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => CustomItineraryPlaceCity::class,
            // 'itineraries' => [],
        ]);
    }
}
