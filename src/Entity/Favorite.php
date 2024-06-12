<?php

namespace App\Entity;

use App\Repository\FavoriteRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FavoriteRepository::class)]
class Favorite
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'favorites')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'favorites')]
    private ?CustomItinerary $customItinerary = null;

    public function getId(): ?int
    {
        return $this->id;
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
