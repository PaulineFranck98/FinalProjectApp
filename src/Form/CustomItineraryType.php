<?php

namespace App\Form;

use App\Entity\User;
use App\Entity\Place;
use App\Entity\CustomItinerary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CustomItineraryType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de l\'itinéraire',
            ])
            // ->add('creationDate', null, [
            //     'widget' => 'single_text',
            // ])
            ->add('departure', TextType::class, [
                'label' => 'Ville de départ',
            ])
            ->add('arrival', TextType::class, [
                'label' => 'Ville d\'arrivée',
            ])
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
