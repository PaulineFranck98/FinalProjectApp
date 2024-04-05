<?php

namespace App\Form;

use App\Entity\Companion;
use App\Entity\CustomItinerary;
use App\Entity\Place;
use App\Entity\Theme;
use App\Entity\Type;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PlaceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('address')
            ->add('city')
            ->add('zipcode')
            ->add('openingHours')
            ->add('website')
            ->add('phoneNumber')
            ->add('description')
            ->add('latitude')
            ->add('longitude')
            ->add('isVerified')
            ->add('types', EntityType::class, [
                'class' => Type::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('themes', EntityType::class, [
                'class' => Theme::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('companions', EntityType::class, [
                'class' => Companion::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
            ->add('customItineraries', EntityType::class, [
                'class' => CustomItinerary::class,
                'choice_label' => 'id',
                'multiple' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Place::class,
        ]);
    }
}
