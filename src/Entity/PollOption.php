<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\PollOptionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: PollOptionRepository::class)]
class PollOption
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\ManyToOne(inversedBy: 'pollOptions')]
    private ?Poll $poll = null;

    /**
     * @var Collection<int, PollAnswer> $pollAnswers
     */
    #[ORM\OneToMany(mappedBy: 'pollOption', targetEntity: PollAnswer::class)]
    private Collection $pollAnswers;

    #[ORM\Column(length: 255)]
    private ?string $content = null;

    public function __construct()
    {
        $this->pollAnswers = new ArrayCollection();
    }

    public function __toString(): string
    {
        return 'a poll option';
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getPoll(): ?Poll
    {
        return $this->poll;
    }

    public function setPoll(?Poll $poll): self
    {
        $this->poll = $poll;

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
            $pollAnswer->setPollOption($this);
        }

        return $this;
    }

    public function removePollAnswer(PollAnswer $pollAnswer): self
    {
        // set the owning side to null (unless already changed)
        if ($this->pollAnswers->removeElement($pollAnswer) && $pollAnswer->getPollOption() === $this) {
            $pollAnswer->setPollOption(null);
        }

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function isUserAnswer(User $user): bool
    {
        return $this->pollAnswers->exists(function ($key, PollAnswer $value) use ($user): bool {
            return $value->getPollOption() === $this && $value->getOwner() === $user;
        });
    }
}
