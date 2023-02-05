<?php

declare(strict_types = 1);

namespace App\Entity\Group;

use App\Entity\Comment;
use App\Entity\User;
use App\Repository\Group\GroupRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: GroupRepository::class)]
#[ORM\Table(name: '`group`')]
class Group
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(length: 255, nullable: true)]
    private string $title;

    #[ORM\Column(length: 255)]
    private ?string $purpose = null;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, GroupUser>
     */
    #[ORM\OneToMany(mappedBy: 'group', targetEntity: GroupUser::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $groupUsers;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $photo = null;

    /**
     * @var Collection<int, GroupRequest>
     */
    #[ORM\OneToMany(mappedBy: 'group', targetEntity: GroupRequest::class)]
    private Collection $groupRequests;

    #[ORM\Column(length: 255)]
    private null|string $type = null;

    #[ORM\Column(length: 3)]
    private string $country = 'en';

    #[ORM\Column]
    private bool $isVisible = true;

    #[ORM\ManyToOne(inversedBy: 'ownedGroups')]
    private ?User $owner = null;

    #[ORM\Column(length: 3)]
    private string $language = 'gb';

    /**
     * @var Collection<int,GroupEvent> $groupEvents
     */
    #[ORM\OneToMany(mappedBy: 'group', targetEntity: GroupEvent::class, orphanRemoval: true)]
    private Collection $groupEvents;

    /**
     * @var Collection<int,Comment> $comments
     */
    #[ORM\OneToMany(mappedBy: 'group', targetEntity: Comment::class)]
    private Collection $comments;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->groupUsers = new ArrayCollection();
        $this->groupRequests = new ArrayCollection();
        $this->groupEvents = new ArrayCollection();
        $this->comments = new ArrayCollection();
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

    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    public function setPurpose(string $purpose): self
    {
        $this->purpose = $purpose;

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

    /**
     * @return Collection<int, GroupUser>
     */
    public function getGroupUsers(): Collection
    {
        return $this->groupUsers;
    }

    public function addGroupUser(GroupUser $groupUser): self
    {
        if (! $this->groupUsers->contains($groupUser)) {
            $this->groupUsers->add($groupUser);
            $groupUser->setGroup($this);
        }

        return $this;
    }

    public function removeGroupUser(GroupUser $groupUser): self
    {
        // set the owning side to null (unless already changed)
        if ($groupUser->getGroup() === $this) {
            $this->groupUsers->removeElement($groupUser);
        }

        return $this;
    }

    public function getPhoto(): ?string
    {
        return $this->photo;
    }

    public function setPhoto(?string $photo): self
    {
        $this->photo = $photo;

        return $this;
    }

    public function isUserMember(User $user): bool
    {
        return $this->getGroupUsers()
            ->exists(function (int $key, GroupUser $groupUser) use ($user): bool {
                return $groupUser->getOwner() === $user;
            });
    }

    public function isUserAdmin(User $user): bool
    {
        return $this->getGroupUsers()
            ->exists(function (int $key, GroupUser $groupUser) use ($user): bool {
                return $groupUser->getOwner() === $user && $groupUser->getRole() === 'ROLE_GROUP_ADMIN';
            });
    }

    public function hasUserRequested(User $user): bool
    {
        return $this->getGroupRequests()
            ->exists(function (int $key, GroupRequest $groupRequest) use ($user): bool {
                return $groupRequest->getOwner() === $user;
            });
    }

    /**
     * @return Collection<int, GroupRequest>
     */
    public function getGroupRequests(): Collection
    {
        return $this->groupRequests;
    }

    public function addGroupRequest(GroupRequest $groupRequest): self
    {
        if (! $this->groupRequests->contains($groupRequest)) {
            $this->groupRequests->add($groupRequest);
            $groupRequest->setGroup($this);
        }

        return $this;
    }

    public function removeGroupRequest(GroupRequest $groupRequest): self
    {
        // set the owning side to null (unless already changed)
        if ($groupRequest->getGroup() === $this) {
            $this->groupRequests->removeElement($groupRequest);
        }

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getCountry(): string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function isIsVisible(): bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
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

    public function getLanguage(): string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Collection<int, GroupEvent>
     */
    public function getGroupEvents(): Collection
    {
        return $this->groupEvents;
    }

    public function addGroupEvent(GroupEvent $groupEvent): self
    {
        if (! $this->groupEvents->contains($groupEvent)) {
            $this->groupEvents->add($groupEvent);
            $groupEvent->setGroup($this);
        }

        return $this;
    }

    public function removeGroupEvent(GroupEvent $groupEvent): self
    {
        // set the owning side to null (unless already changed)
        if ($this->groupEvents->removeElement($groupEvent) && $groupEvent->getGroup() === $this) {
            $groupEvent->setGroup(null);
        }

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
            $comment->setGroup($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        // set the owning side to null (unless already changed)
        if ($this->comments->removeElement($comment) && $comment->getGroup() === $this) {
            $comment->setGroup(null);
        }

        return $this;
    }
}
