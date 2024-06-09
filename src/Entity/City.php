<?php

namespace App\Entity;

use App\Repository\CityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CityRepository::class)]
class City
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $cityCode = null;

    #[ORM\Column(length: 255)]
    private ?string $cityName = null;

    /**
     * @var Collection<int, CustomItinerary>
     */
    #[ORM\ManyToMany(targetEntity: CustomItinerary::class, mappedBy: 'cities')]
    private Collection $customItineraries;

    /**
     * @var Collection<int, Place>
     */
    #[ORM\OneToMany(targetEntity: Place::class, mappedBy: 'city')]
    private Collection $places;

    /**
     * @var Collection<int, CustomItineraryPlaceCity>
     */
    #[ORM\OneToMany(targetEntity: CustomItineraryPlaceCity::class, mappedBy: 'city')]
    private Collection $customItineraryPlaceCities;



    public function __construct()
    {
        $this->customItineraries = new ArrayCollection();
        $this->places = new ArrayCollection();
        $this->customItineraryPlaceCities = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCityCode(): ?string
    {
        return $this->cityCode;
    }

    public function setCityCode(string $cityCode): static
    {
        $this->cityCode = $cityCode;

        return $this;
    }

    public function getCityName(): ?string
    {
        return $this->cityName;
    }

    public function setCityName(string $cityName): static
    {
        $this->cityName = $cityName;

        return $this;
    }


    /**
     * @return Collection<int, CustomItinerary>
     */
    public function getCustomItineraries(): Collection
    {
        return $this->customItineraries;
    }

    public function addCustomItinerary(CustomItinerary $customItinerary): static
    {
        if (!$this->customItineraries->contains($customItinerary)) {
            $this->customItineraries->add($customItinerary);
            $customItinerary->addCity($this);
        }

        return $this;
    }

    public function removeCustomItinerary(CustomItinerary $customItinerary): static
    {
        if ($this->customItineraries->removeElement($customItinerary)) {
            $customItinerary->removeCity($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Place>
     */
    public function getPlaces(): Collection
    {
        return $this->places;
    }

    public function addPlace(Place $place): static
    {
        if (!$this->places->contains($place)) {
            $this->places->add($place);
            $place->setCity($this);
        }

        return $this;
    }

    public function removePlace(Place $place): static
    {
        if ($this->places->removeElement($place)) {
            // set the owning side to null (unless already changed)
            if ($place->getCity() === $this) {
                $place->setCity(null);
            }
        }

        return $this;
    }


    public function __toString(){
        return $this->getCityName() ?: '';
    }

    /**
     * @return Collection<int, CustomItineraryPlaceCity>
     */
    public function getCustomItineraryPlaceCities(): Collection
    {
        return $this->customItineraryPlaceCities;
    }

    public function addCustomItineraryPlaceCity(CustomItineraryPlaceCity $customItineraryPlaceCity): static
    {
        if (!$this->customItineraryPlaceCities->contains($customItineraryPlaceCity)) {
            $this->customItineraryPlaceCities->add($customItineraryPlaceCity);
            $customItineraryPlaceCity->setCity($this);
        }

        return $this;
    }

    public function removeCustomItineraryPlaceCity(CustomItineraryPlaceCity $customItineraryPlaceCity): static
    {
        if ($this->customItineraryPlaceCities->removeElement($customItineraryPlaceCity)) {
            // set the owning side to null (unless already changed)
            if ($customItineraryPlaceCity->getCity() === $this) {
                $customItineraryPlaceCity->setCity(null);
            }
        }

        return $this;
    }
}
