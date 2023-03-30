<?php

declare(strict_types = 1);

namespace App\Entity\Auction;

use App\Entity\Bookmark;
use App\Entity\Comment;
use App\Entity\Image;
use App\Entity\User;
use App\Repository\ItemRepository;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;
use Bazinga\GeocoderBundle\Mapping\Annotations as Geocoder;


#[ORM\Entity(repositoryClass: ItemRepository::class)]
#[ORM\Index(columns: ['title', 'price'], name: 'item_index')]
#[Geocoder\Geocodeable]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private null|Uuid $id = null;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private null|string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private null|string $description = null;

    #[Assert\Positive]
    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private null|string $price = null;

    #[ORM\Column]
    private bool $isApproved = false;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column]
    private DateTimeImmutable $startAt;

    #[ORM\Column]
    private DateTimeImmutable $endAt;

    #[Assert\NotBlank]
    #[ORM\Column(length: 255)]
    private null|string $condition = null;

    /**
     * @var Collection<int, Image>
     */
    #[ORM\OneToMany(mappedBy: 'item', targetEntity: Image::class, cascade: ['persist'])]
    private Collection $images;

    #[Assert\Currency]
    #[ORM\Column(length: 3)]
    private string $currency = 'EUR';

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(mappedBy: 'marketItem', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\Column(nullable: true)]
    private bool $isSold = false;

    /**
     * @var Collection<int, Bookmark>
     */
    #[ORM\OneToMany(mappedBy: 'item', targetEntity: Bookmark::class)]
    private Collection $bookmarks;

    /**
     * @var Collection<int, Offer>
     */
    #[ORM\OneToMany(mappedBy: 'item', targetEntity: Offer::class)]
    private Collection $offers;

    #[ORM\ManyToOne]
    private null|Offer $offer = null;

    #[ORM\Column(length: 255, nullable: true)]
    private null|string $status = null;

    #[Assert\NotBlank]
    #[Geocoder\Address]
    #[ORM\Column(length: 255, nullable: true)]
    private null|string $address = null;

    #[ORM\Column(nullable: true)]
    #[Geocoder\Longitude]
    private null|float $longitude = null;

    #[ORM\Column(nullable: true)]
    #[Geocoder\Latitude]
    private null|float $latitude = null;

    #[ORM\ManyToOne(inversedBy: 'classifieds')]
    #[ORM\JoinColumn(nullable: false)]
    private null|User $owner = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->startAt = CarbonImmutable::now()->addDay()->toDateTimeImmutable();
        $this->endAt = CarbonImmutable::now()->addWeek()->toDateTimeImmutable();
        $this->images = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->bookmarks = new ArrayCollection();
        $this->offers = new ArrayCollection();
    }

    public function getId(): null|Uuid
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    public function getCreatedAt(): null|DateTimeImmutable|CarbonImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(string $condition): self
    {
        $this->condition = $condition;

        return $this;
    }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): self
    {
        if (! $this->images->contains($image)) {
            $this->images->add($image);
            $image->setItem($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        // set the owning side to null (unless already changed)
        if ($this->images->removeElement($image) && $image->getItem() === $this) {
            $image->setItem(null);
        }

        return $this;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): self
    {
        if (! $this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setMarketItem($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        // set the owning side to null (unless already changed)
        if ($this->comments->removeElement($comment) && $comment->getMarketItem() === $this) {
            $comment->setMarketItem(null);
        }

        return $this;
    }

    public function getIsSold(): ?bool
    {
        return $this->isSold;
    }

    public function setIsSold(bool $isSold): self
    {
        $this->isSold = $isSold;

        return $this;
    }

    /**
     * @return Collection<int, Bookmark>
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function addBookmark(Bookmark $bookmark): self
    {
        if (! $this->bookmarks->contains($bookmark)) {
            $this->bookmarks->add($bookmark);
            $bookmark->setItem($this);
        }

        return $this;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        // set the owning side to null (unless already changed)
        if ($this->bookmarks->removeElement($bookmark) && $bookmark->getItem() === $this) {
            $bookmark->setItem(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Offer>
     */
    public function getOffers(): Collection
    {
        return $this->offers;
    }

    public function addBid(Offer $bid): self
    {
        if (! $this->offers->contains($bid)) {
            $this->offers->add($bid);
            $bid->setItem($this);
        }

        return $this;
    }

    public function removeBid(Offer $bid): self
    {
        // set the owning side to null (unless already changed)
        if ($this->offers->removeElement($bid) && $bid->getItem() === $this) {
            $bid->setItem(null);
        }

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
     * @return Offer|null
     */
    public function getOffer(): ?Offer
    {
        return $this->offer;
    }

    /**
     * @param Offer|null $offer
     */
    public function setOffer(?Offer $offer): void
    {
        $this->offer = $offer;
    }

    public function getLongitude(): ?float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): self
    {
        $this->longitude = $longitude;

        return $this;
    }

    public function getLatitude(): ?float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): self
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getAddress(): ?string
    {
        return $this->address;
    }

    /**
     * @param string|null $address
     */
    public function setAddress(?string $address): void
    {
        $this->address = $address;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getStartAt(): null|DateTimeImmutable|CarbonImmutable
    {
        return $this->startAt;
    }

    /**
     * @param DateTimeImmutable $startAt
     */
    public function setStartAt(DateTimeImmutable $startAt): void
    {
        $this->startAt = $startAt;
    }

    /**
     * @return DateTimeImmutable
     */
    public function getEndAt(): null|DateTimeImmutable|CarbonImmutable
    {
        return $this->endAt;
    }

    /**
     * @param DateTimeImmutable $endAt
     */
    public function setEndAt(DateTimeImmutable $endAt): void
    {
        $this->endAt = $endAt;
    }

    public function getIsActive() : bool
    {
        return CarbonImmutable::now()->isBetween($this->startAt, $this->endAt);
    }

}
