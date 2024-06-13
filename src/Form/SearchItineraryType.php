<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\CustomItinerary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class SearchItineraryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('departure', EntityType::class, [
                'class' => City::class,
                'label' => 'Ville de départ',
                'choice_label' => 'cityName',
                'required' => false,
            ])
            ->add('arrival', EntityType::class, [
                'class' => City::class,
                'label' => 'Ville d\'arrivée',
                'choice_label' => 'cityName',
                'required' => false,
            ])
            ->add('duration', IntegerType::class, [
                'required' => false,
                'label' => 'Durée',
            ])
            ->add('valider', SubmitType::class, [
                'label' => 'Rechercher'
            ])
        ;
            
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // 'data_class' => CustomItinerary::class,
        ]);
    }

}