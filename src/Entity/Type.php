<?php

namespace App\Entity;

use App\Repository\TypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TypeRepository::class)]
class Type
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Place::class, inversedBy: 'types')]
    private Collection $type_place;

    public function __construct()
    {
        $this->type_place = new ArrayCollection();
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
    public function getTypePlace(): Collection
    {
        return $this->type_place;
    }

    public function addTypePlace(Place $typePlace): static
    {
        if (!$this->type_place->contains($typePlace)) {
            $this->type_place->add($typePlace);
        }

        return $this;
    }

    public function removeTypePlace(Place $typePlace): static
    {
        $this->type_place->removeElement($typePlace);

        return $this;
    }

    public function __toString(){
        return $this->getName();
    }
}
