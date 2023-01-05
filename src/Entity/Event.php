<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\EventRepository;
use Carbon\Carbon;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(length: 255, nullable: false)]
    private string $title;

    #[ORM\Column(length: 255, nullable: false)]
    private string $address;

    #[ORM\Column]
    private DateTimeImmutable $startAt;

    #[ORM\Column(nullable: true)]
    private null|DateTimeImmutable $endAt = null;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\ManyToOne(inversedBy: 'authoredEvents')]
    private User $owner;

    /**
     * @var Collection<int, EventUser>
     */
    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventUser::class, cascade: ['persist', 'remove'])]
    private Collection $users;

    /**
     * @var Collection<int, EventRequest>
     */
    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventRequest::class, cascade: ['persist', 'remove'])]
    private Collection $eventRequests;

    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->eventRequests = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getTitle();
    }

    public function getId(): ?Uuid
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(string $address): self
    {
        $this->address = $address;

        return $this;
    }

    public function getStartAt(): ?DateTimeImmutable
    {
        return $this->startAt;
    }

    public function setStartAt(DateTimeImmutable $startAt): self
    {
        $this->startAt = $startAt;

        return $this;
    }

    public function getEndAt(): ?DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(?DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

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
     * @return Collection<int, EventUser>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(EventUser $user): self
    {
        if (! $this->users->contains($user)) {
            $this->users->add($user);
            $user->setEvent($this);
        }

        return $this;
    }

    public function removeUser(EventUser $user): self
    {
        // set the owning side to null (unless already changed)
        $this->users->removeElement($user);

        return $this;
    }

    public function isComplete(): bool
    {
        return Carbon::parse($this->startAt)->isAfter($this->endAt);
    }

    public function getDuration(): int
    {
        return Carbon::parse($this->startAt)->diffInRealHours($this->endAt);
    }

    public function getNowDurationDiff(): int
    {
        if ($this->isPending()) {
            return 0;
        }

        if ($this->isComplete()) {
            return $this->getDuration();
        }

        return Carbon::now()->subHours($this->getDuration())->diffInRealHours();
    }

    public function isPending(): bool
    {
        return Carbon::parse($this->startAt)->isBefore($this->endAt);
    }

    public function isHappening(): bool
    {
        return Carbon::now()->isBetween($this->startAt, $this->endAt);
    }

    /**
     * @return Collection<int, EventRequest>
     */
    public function getEventRequests(): Collection
    {
        return $this->eventRequests;
    }

    public function addEventRequest(EventRequest $eventRequest): self
    {
        if (! $this->eventRequests->contains($eventRequest)) {
            $this->eventRequests->add($eventRequest);
            $eventRequest->setEvent($this);
        }

        return $this;
    }

    public function removeEventRequest(EventRequest $eventRequest): self
    {
        $this->eventRequests->removeElement($eventRequest);

        return $this;
    }

    public function getHasBeenRejected(User $user): bool
    {
        return $this->getEventRequests()
            ->exists(function (int $key, EventRequest $eventRequest) use ($user): bool {
                return $eventRequest->getOwner() === $user && $eventRequest->isIsAccepted() === false;
            });
    }

    public function hasUserRequested(User $user): bool
    {
        return $this->getEventRequests()
            ->exists(function (int $key, EventRequest $eventRequest) use ($user): bool {
                return $eventRequest->getOwner() === $user;
            });
    }

    public function getIsUserAttending(User $user): bool
    {
        return $this->getUsers()
            ->exists(function (int $key, EventUser $eventUser) use ($user): bool {
                return $eventUser->getOwner() === $user;
            });
    }

    public function getIsRequestPending(User $user): bool
    {
        return $this->getEventRequests()
            ->exists(function (int $key, EventRequest $eventRequest) use ($user): bool {
                return $eventRequest->getOwner() === $user && $eventRequest->isIsAccepted() === null;
            });
    }
}
