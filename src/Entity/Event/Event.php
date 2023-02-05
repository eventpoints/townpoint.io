<?php

declare(strict_types = 1);

namespace App\Entity\Event;

use App\Entity\Comment;
use App\Entity\Group\GroupEvent;
use App\Entity\User;
use App\Repository\Event\EventRepository;
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
    private Collection $eventUsers;

    /**
     * @var Collection<int, EventRequest>
     */
    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventRequest::class, cascade: ['persist', 'remove'])]
    private Collection $eventRequests;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Comment::class)]
    private Collection $comments;

    /**
     * @var Collection<int, EventInvite>
     */
    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventInvite::class)]
    private Collection $eventInvites;

    #[ORM\Column]
    private bool $isTicketed = false;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventRejection::class)]
    private Collection $eventRejections;

    #[ORM\OneToOne(inversedBy: 'event', targetEntity: GroupEvent::class, cascade: ['persist', 'remove'])]
    #[ORM\JoinColumn(nullable: true)]
    private null|GroupEvent $groupEvent = null;

    public function __construct()
    {
        $this->eventUsers = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
        $this->eventRequests = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->eventInvites = new ArrayCollection();
        $this->eventRejections = new ArrayCollection();
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
    public function getEventUsers(): Collection
    {
        return $this->eventUsers;
    }

    public function addEventUser(EventUser $user): self
    {
        if (! $this->eventUsers->contains($user)) {
            $this->eventUsers->add($user);
            $user->setEvent($this);
        }

        return $this;
    }

    public function removeEventUser(EventUser $user): self
    {
        // set the owning side to null (unless already changed)
        $this->eventUsers->removeElement($user);

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
                return $eventRequest->getOwner() === $user;
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
        return $this->getEventUsers()
            ->exists(function (int $key, EventUser $eventUser) use ($user): bool {
                return $eventUser->getOwner() === $user;
            });
    }

    public function getIsRequestPending(User $user): bool
    {
        return $this->getEventRequests()
            ->exists(function (int $key, EventRequest $eventRequest) use ($user): bool {
                return $eventRequest->getOwner() === $user;
            });
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
            $comment->setEvent($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        // set the owning side to null (unless already changed)
        if ($this->comments->removeElement($comment) && $comment->getEvent() === $this) {
            $comment->setEvent(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, EventInvite>
     */
    public function getEventInvites(): Collection
    {
        return $this->eventInvites;
    }

    public function addEventInvite(EventInvite $eventInvite): self
    {
        if (! $this->eventInvites->contains($eventInvite)) {
            $this->eventInvites->add($eventInvite);
            $eventInvite->setEvent($this);
        }

        return $this;
    }

    public function removeEventInvite(EventInvite $eventInvite): self
    {
        // set the owning side to null (unless already changed)
        if ($eventInvite->getEvent() === $this) {
            $this->eventInvites->removeElement($eventInvite);
        }

        return $this;
    }

    public function hasBeenInvited(User $user): bool
    {
        return $this->getEventInvites()
            ->exists(function (int $key, EventInvite $eventInvite) use ($user): bool {
                return $eventInvite->getOwner() === $user;
            });
    }

    public function isIsTicketed(): bool
    {
        return $this->isTicketed;
    }

    public function setIsTicketed(bool $isTicketed): self
    {
        $this->isTicketed = $isTicketed;

        return $this;
    }

    /**
     * @return Collection<int, EventRejection>
     */
    public function getEventRejections(): Collection
    {
        return $this->eventRejections;
    }

    public function addEventRejection(EventRejection $eventRejection): self
    {
        if (! $this->eventRejections->contains($eventRejection)) {
            $this->eventRejections->add($eventRejection);
            $eventRejection->setEvent($this);
        }

        return $this;
    }

    public function removeEventRejection(EventRejection $eventRejection): self
    {
        // set the owning side to null (unless already changed)
        if ($this->eventRejections->removeElement($eventRejection) && $eventRejection->getEvent() === $this) {
            $eventRejection->setEvent(null);
        }

        return $this;
    }

    public function hasUserRejectedInvitation(User $user): bool
    {
        return $this->getEventRejections()
            ->exists(function (int $key, EventRejection $eventRejection) use ($user): bool {
                return $eventRejection->getOwner() === $user;
            });
    }

    public function getGroupEvent(): null|GroupEvent
    {
        return $this->groupEvent;
    }

    public function setGroupEvent(null|GroupEvent $groupEvent): void
    {
        $this->groupEvent = $groupEvent;
    }

    public function isSameWeek(): bool
    {
        $date = Carbon::parse($this->createdAt);

        return $date->isSameWeek();
    }
}
