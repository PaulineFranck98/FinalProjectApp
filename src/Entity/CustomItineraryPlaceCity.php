<?php

namespace App\Entity;

use App\Repository\CustomItineraryPlaceCityRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomItineraryPlaceCityRepository::class)]
class CustomItineraryPlaceCity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'customItineraryPlaceCities')]
    private ?Place $place = null;

    #[ORM\ManyToOne(inversedBy: 'customItineraryPlaceCities')]
    private ?City $city = null;

    #[ORM\ManyToOne(inversedBy: 'customItineraryPlaceCities')]
    private ?CustomItinerary $customItinerary = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlace(): ?Place
    {
        return $this->place;
    }

    public function setPlace(?Place $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getCity(): ?City
    {
        return $this->city;
    }

    public function setCity(?City $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getCustomItinerary(): ?CustomItinerary
    {
        return $this->customItinerary;
    }

    public function setCustomItinerary(?CustomItinerary $customItinerary): static
    {
        $this->customItinerary = $customItinerary;

        return $this;
    }
}
