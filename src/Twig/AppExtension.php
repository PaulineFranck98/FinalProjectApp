<?php

namespace App\Twig; 

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('stars', [$this, 'stars'], ['is_safe' => ['html']]),
        ];
    }

    public function stars($note)
    {

        $html = '';
        $fullStars = floor($note); // Nombre d'étoiles pleines
        $halfStar = $note - $fullStars; // Nombre d'étoiles semi-pleines (0 ou 0.5)
        $emptyStars = 5 - $fullStars - ($halfStar > 0 ? 1 : 0); // Nombre d'étoiles vides
    
        // Ajout des étoiles pleines
        for ($i = 0; $i < $fullStars; $i++) {
            $html .= '<i class="fas fa-star"></i>';
        }
    
        // Ajout de l'étoile semi-pleine si nécessaire
        if ($halfStar > 0) {
            $html .= '<i class="fas fa-star-half-alt"></i>';
        }
    
        // Ajout des étoiles vides
        for ($i = 0; $i < $emptyStars; $i++) {
            $html .= '<i class="far fa-star"></i>';
        }
    
        return $html;
    
        // $html = '';
        // for ($i = 0; $i < $note; $i++) {
        //     $html .= '<i class="fas fa-star"></i>'; //sous réserve que fontawesome soit chargé
        // }
        // for ($i = 0; $i < 5 - $note; $i++) {
        //     $html .= '<i class="far fa-star"></i>'; //sous réserve que fontawesome soit chargé
        // }

        // return $html;
    }
}