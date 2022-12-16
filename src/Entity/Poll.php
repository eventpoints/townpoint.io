<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\PollRepository;
use Carbon\Carbon;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;
use Symfony\UX\Turbo\Attribute\Broadcast;

#[ORM\Entity(repositoryClass: PollRepository::class)]
#[Broadcast]
class Poll
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(length: 400)]
    private ?string $motion = null;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, PollOption> $pollOptions
     */
    #[ORM\OneToMany(mappedBy: 'poll', targetEntity: PollOption::class, cascade: ['persist'])]
    private Collection $pollOptions;

    /**
     * @var Collection<int, PollAnswer> $pollAnswers
     */
    #[ORM\OneToMany(mappedBy: 'poll', targetEntity: PollAnswer::class)]
    private Collection $pollAnswers;

    #[ORM\ManyToOne(inversedBy: 'polls')]
    private ?User $owner = null;

    #[ORM\Column]
    private ?DateTimeImmutable $endAt;

    public function __construct()
    {
        $this->pollOptions = new ArrayCollection();
        $this->pollAnswers = new ArrayCollection();
        $this->createdAt = new DateTimeImmutable();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getMotion(): ?string
    {
        return $this->motion;
    }

    public function setMotion(string $motion): self
    {
        $this->motion = $motion;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, PollOption>
     */
    public function getPollOptions(): Collection
    {
        return $this->pollOptions;
    }

    public function addPollOption(PollOption $pollOption): self
    {
        if (! $this->pollOptions->contains($pollOption)) {
            $this->pollOptions->add($pollOption);
            $pollOption->setPoll($this);
        }

        return $this;
    }

    public function removePollOption(PollOption $pollOption): self
    {
        // set the owning side to null (unless already changed)
        if ($this->pollOptions->removeElement($pollOption) && $pollOption->getPoll() === $this) {
            $pollOption->setPoll(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, PollAnswer>
     */
    public function getPollAnswers(): Collection
    {
        return $this->pollAnswers;
    }

    public function addPollAnswer(PollAnswer $pollAnswer): self
    {
        if (! $this->pollAnswers->contains($pollAnswer)) {
            $this->pollAnswers->add($pollAnswer);
            $pollAnswer->setPoll($this);
        }

        return $this;
    }

    public function removePollAnswer(PollAnswer $pollAnswer): self
    {
        // set the owning side to null (unless already changed)
        if ($this->pollAnswers->removeElement($pollAnswer) && $pollAnswer->getPoll() === $this) {
            $pollAnswer->setPoll(null);
        }

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

    public function getEndAt(): ?DateTimeImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function hasEnded(): bool
    {
        return Carbon::now()->isAfter($this->endAt);
    }

    public function hasUserVoted(User $user): bool
    {
        return $this->pollAnswers->exists(function ($key, PollAnswer $pollAnswer) use ($user): bool {
            return $pollAnswer->getOwner() === $user;
        });
    }

    public function getUserPollAnswer(User $user): PollAnswer|false
    {
        return $this->pollAnswers->filter(function (PollAnswer $pollAnswer) use ($user): bool {
            return $pollAnswer->getOwner() === $user && $pollAnswer->getPoll() === $this;
        })->first();
    }
}
