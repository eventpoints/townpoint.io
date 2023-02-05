<?php

namespace App\Entity\Market;

use App\Entity\Comment;
use App\Entity\Conversation;
use App\Entity\Image;
use App\Entity\User;
use App\Repository\ItemRepository;
use Carbon\Carbon;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ItemRepository::class)]
class Item
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private ?Uuid $id;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private ?string $price = null;

    #[ORM\Column]
    private bool $isApproved = false;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(length: 255)]
    private ?string $condition = null;

    #[ORM\ManyToOne(inversedBy: 'marketItems')]
    private User $owner;

    #[ORM\OneToMany(mappedBy: 'marketItem', targetEntity: Image::class, cascade: ['persist'])]
    private Collection $images;

    #[ORM\Column(length: 3)]
    private string $currency = 'EUR';

    #[ORM\OneToMany(mappedBy: 'marketItem', targetEntity: Comment::class)]
    private Collection $comments;

    #[ORM\Column(nullable: true)]
    private bool $isAcceptingPriceOffers = false;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->images = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->conversations = new ArrayCollection();
    }

    public function getId(): ?Uuid
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

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCreatedAtAgo(): ?string
    {
        return Carbon::parse($this->createdAt)->diffForHumans();
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

    public function getOwner(): User
    {
        return $this->owner;
    }

    public function setOwner(User $owner): self
    {
        $this->owner = $owner;

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
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setMarketItem($this);
        }

        return $this;
    }

    public function removeImage(Image $image): self
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getMarketItem() === $this) {
                $image->setMarketItem(null);
            }
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
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setMarketItem($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getMarketItem() === $this) {
                $comment->setMarketItem(null);
            }
        }

        return $this;
    }

    public function getIsAcceptingPriceOffers(): ?bool
    {
        return $this->isAcceptingPriceOffers;
    }

    public function setIsAcceptingPriceOffers(bool $isAcceptingPriceOffers): self
    {
        $this->isAcceptingPriceOffers = $isAcceptingPriceOffers;

        return $this;
    }

}
