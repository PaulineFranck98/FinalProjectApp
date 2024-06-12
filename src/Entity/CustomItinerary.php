<?php

namespace App\Entity;

use App\Repository\CustomItineraryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CustomItineraryRepository::class)]
class CustomItinerary
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $creationDate = null;

    #[ORM\ManyToOne(inversedBy: 'customItineraries')]
    private ?User $user = null;

    #[ORM\ManyToMany(targetEntity: Place::class, inversedBy: 'customItineraries')]
    private Collection $place;

    // #[ORM\Column(length: 255, nullable: true)]
    // private ?string $departure = null;

    // #[ORM\Column(length: 255, nullable: true)]
    // private ?string $arrival = null;

    #[ORM\ManyToOne(targetEntity: City::class)]
    private ?City $departure = null;

    #[ORM\ManyToOne(targetEntity: City::class)]
    private ?City $arrival = null;

    /**
     * @var Collection<int, City>
     */
    #[ORM\ManyToMany(targetEntity: City::class, inversedBy: 'customItineraries', cascade:['persist'])]
    private Collection $cities;

    #[ORM\Column(nullable: true)]
    private ?int $duration = null;

    #[ORM\Column(nullable: true)]
    private ?bool $isPublic = null;

    /**
     * @var Collection<int, CustomItineraryPlaceCity>
     */
    #[ORM\OneToMany(targetEntity: CustomItineraryPlaceCity::class, mappedBy: 'customItinerary')]
    private Collection $customItineraryPlaceCities;

    /**
     * @var Collection<int, Favorite>
     */
    #[ORM\OneToMany(targetEntity: Favorite::class, mappedBy: 'customItinerary')]
    private Collection $favorites;

    public function __construct()
    {
        $this->place = new ArrayCollection();
        $this->cities = new ArrayCollection();
        $this->customItineraryPlaceCities = new ArrayCollection();
        $this->favorites = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreationDate(): ?\DateTimeInterface
    {
        return $this->creationDate;
    }

    public function setCreationDate(\DateTimeInterface $creationDate): static
    {
        $this->creationDate = $creationDate;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection<int, Place>
     */
    public function getPlace(): Collection
    {
        return $this->place;
    }

    public function addPlace(Place $place): static
    {
        if (!$this->place->contains($place)) {
            $this->place->add($place);
        }

        return $this;
    }

    public function removePlace(Place $place): static
    {
        $this->place->removeElement($place);

        return $this;
    }

    public function getDeparture(): ?City
    {
        return $this->departure;
    }

    public function setDeparture(?City $departure): static
    {
        $this->departure = $departure;

        return $this;
    }

    public function getArrival(): ?City
    {
        return $this->arrival;
    }

    public function setArrival(?City $arrival): static
    {
        $this->arrival = $arrival;

        return $this;
    }

    /**
     * @return Collection<int, City>
     */
    public function getCities(): Collection
    {
        return $this->cities;
    }

    public function addCity(City $city): static
    {
        if (!$this->cities->contains($city)) {
            $this->cities->add($city);
        }

        return $this;
    }

    public function removeCity(City $city): static
    {
        $this->cities->removeElement($city);

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function isIsPublic(): ?bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(?bool $isPublic): static
    {
        $this->isPublic = $isPublic;

        return $this;
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
            $customItineraryPlaceCity->setCustomItinerary($this);
        }

        return $this;
    }

    public function removeCustomItineraryPlaceCity(CustomItineraryPlaceCity $customItineraryPlaceCity): static
    {
        if ($this->customItineraryPlaceCities->removeElement($customItineraryPlaceCity)) {
            // set the owning side to null (unless already changed)
            if ($customItineraryPlaceCity->getCustomItinerary() === $this) {
                $customItineraryPlaceCity->setCustomItinerary(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Favorite>
     */
    public function getFavorites(): Collection
    {
        return $this->favorites;
    }

    public function addFavorite(Favorite $favorite): static
    {
        if (!$this->favorites->contains($favorite)) {
            $this->favorites->add($favorite);
            $favorite->setCustomItinerary($this);
        }

        return $this;
    }

    public function removeFavorite(Favorite $favorite): static
    {
        if ($this->favorites->removeElement($favorite)) {
            // set the owning side to null (unless already changed)
            if ($favorite->getCustomItinerary() === $this) {
                $favorite->setCustomItinerary(null);
            }
        }

        return $this;
    }
}
