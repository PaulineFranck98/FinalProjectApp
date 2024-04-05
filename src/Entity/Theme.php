<?php

namespace App\Entity;

use App\Repository\ThemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ThemeRepository::class)]
class Theme
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: Place::class, inversedBy: 'themes')]
    private Collection $theme_place;

    public function __construct()
    {
        $this->theme_place = new ArrayCollection();
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
    public function getThemePlace(): Collection
    {
        return $this->theme_place;
    }

    public function addThemePlace(Place $themePlace): static
    {
        if (!$this->theme_place->contains($themePlace)) {
            $this->theme_place->add($themePlace);
        }

        return $this;
    }

    public function removeThemePlace(Place $themePlace): static
    {
        $this->theme_place->removeElement($themePlace);

        return $this;
    }

    public function __toString(){
        return $this->getName();
    }
}
