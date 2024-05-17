<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\CustomItinerary;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class CityType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        // $builder
        //     ->add('cityCode', TextType::class)
        //     // ->add('customItineraries', EntityType::class, [
        //     //     'class' => CustomItinerary::class,
        //     //     'choice_label' => 'id',
        //     //     'multiple' => true,
        //     // ])
        // ;
        // $builder
        //     ->add('city', EntityType::class, [
        //         'class' => City::class,
        //         'choice_label' => 'cityName',
        //         'choice_value' => 'id',
        //         'placeholder' => 'Choisissez une ville',
        //         'attr' => ['onChange' => 'this.form.submit()']
        //     ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => City::class,
        ]);
    }
}
