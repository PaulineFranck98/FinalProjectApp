<?php

namespace App\Entity;

use ORM\Column;
use App\Entity\Post;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PlaceRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

#[ORM\Entity(repositoryClass: PlaceRepository::class)]
class Place
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $address = null;

    // #[ORM\Column(length: 255)]
    // private ?string $city = null;

    #[ORM\Column]
    private ?int $zipcode = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $openingHours = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $website = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phoneNumber = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type:"decimal", precision:10, scale:8)]
    private ?float $latitude = null;

    #[ORM\Column(type:"decimal", precision:11, scale:8)]
    private ?float $longitude = null;

    #[ORM\Column]
    private ?bool $isVerified = true;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'place')]
    private Collection $posts;

    // en créant un lieu, il faut également persist les images
    #[ORM\OneToMany(targetEntity: Image::class, mappedBy: 'place', orphanRemoval: true, cascade:['persist'] )]
    private $images;

    #[ORM\ManyToOne(inversedBy: 'places')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Type $type = null;

    #[ORM\ManyToOne(inversedBy: 'places')]
    private ?City $city = null;


    #[ORM\ManyToMany(targetEntity: Theme::class,  inversedBy: 'places')]
    private Collection $themes;

    #[ORM\ManyToMany(targetEntity: Companion::class,  inversedBy: 'places')]
    private Collection $companions;

    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'favorite')]
    private Collection $users;

    #[ORM\OneToMany(targetEntity: Rating::class, mappedBy: 'place')]
    private Collection $ratings;

    #[ORM\ManyToMany(targetEntity: CustomItinerary::class, mappedBy: 'place')]
    private Collection $customItineraries;

   
    public function __construct()
    {
        $this->posts = new ArrayCollection();
        $this->images = new ArrayCollection();
        $this->themes = new ArrayCollection();
        $this->companions = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->ratings = new ArrayCollection();
        $this->customItineraries = new ArrayCollection();
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

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): static
    {
        $this->address = $address;

        return $this;
    }

    // public function getCity(): ?string
    // {
    //     return $this->city;
    // }

    // public function setCity(string $city): static
    // {
    //     $this->city = $city;

    //     return $this;
    // }

    public function getZipcode(): ?string
    {
        return $this->zipcode;
    }

    public function setZipcode(string $zipcode): static
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getOpeningHours(): ?string
    {
        return $this->openingHours;
    }

    public function setOpeningHours(string $openingHours): static
    {
        $this->openingHours = $openingHours;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): static
    {
        $this->website = $website;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?string $phoneNumber): static
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): static
    {
        $this->latitude = $latitude;

        return $this;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): static
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
    {
        $this->type = $type;

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setPlace($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            // set the owning side to null (unless already changed)
            if ($post->getPlace() === $this) {
                $post->setPlace(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            // $this->images->add($image);
            $this->images[] = $image;
            $image->setPlace($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getPlace() === $this) {
                $image->setPlace(null);
            }
        }

        return $this;
    }


   

    /**
     * @return Collection<int, Theme>
     */
    public function getThemes(): Collection
    {
        return $this->themes;
    }

    public function addTheme(Theme $theme): static
    {
        if (!$this->themes->contains($theme)) {
            $this->themes->add($theme);
            
        }

        return $this;
    }

    public function removeTheme(Theme $theme): static
    {
        $this->themes->removeElement($theme);

        return $this;
    }

    /**
     * @return Collection<int, Companion>
     */
    public function getCompanions(): Collection
    {
        return $this->companions;
    }

    public function addCompanion(Companion $companion): static
    {
        if (!$this->companions->contains($companion)) {
            $this->companions->add($companion);
        }

        return $this;
    }

    public function removeCompanion(Companion $companion): static
    {
        $this->companions->removeElement($companion);
    
        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addFavorite($this);
        }

        return $this;
    }

    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeFavorite($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Rating>
     */
    public function getRatings(): Collection
    {
        return $this->ratings;
    }

    public function addRating(Rating $rating): static
    {
        if (!$this->ratings->contains($rating)) {
            $this->ratings->add($rating);
            $rating->setPlace($this);
        }

        return $this;
    }

    public function removeRating(Rating $rating): static
    {
        if ($this->ratings->removeElement($rating)) {
            // set the owning side to null (unless already changed)
            if ($rating->getPlace() === $this) {
                $rating->setPlace(null);
            }
        }

        return $this;
    }

    public function __toString(){
        return $this->getName();
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
            $customItinerary->addPlace($this);
        }

        return $this;
    }

    public function removeCustomItinerary(CustomItinerary $customItinerary): static
    {
        if ($this->customItineraries->removeElement($customItinerary)) {
            $customItinerary->removePlace($this);
        }

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
}

