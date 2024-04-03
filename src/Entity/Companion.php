<?php

namespace App\Entity;

use App\Repository\CompanionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CompanionRepository::class)]
class Companion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Place::class, inversedBy: 'companions')]
    private Collection $companion_place;

    public function __construct()
    {
        $this->companion_place = new ArrayCollection();
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

    /**
     * @return Collection<int, Place>
     */
    public function getCompanionPlace(): Collection
    {
        return $this->companion_place;
    }

    public function addCompanionPlace(Place $companionPlace): static
    {
        if (!$this->companion_place->contains($companionPlace)) {
            $this->companion_place->add($companionPlace);
        }

        return $this;
    }

    public function removeCompanionPlace(Place $companionPlace): static
    {
        $this->companion_place->removeElement($companionPlace);

        return $this;
    }
}
