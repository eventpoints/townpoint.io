<?php

declare(strict_types = 1);

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;


use App\Repository\ProjectRepository;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private string $title;

    #[ORM\ManyToOne(inversedBy: 'projects')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $owner = null;

    #[ORM\Column(nullable: true)]
    private null|bool $isComplete = null;

    #[ORM\Column]
    private bool $isPublic = false;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private DateTimeImmutable|CarbonImmutable $endAt;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[Assert\Choice(choices: ['SPOT_LIGHT', 'STAR_LIGHT'])]
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $type = null;

    #[ORM\OneToOne(targetEntity: Project::class)]
    #[ORM\JoinColumn(nullable: true)]
    private Project|null $project = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
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

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): self
    {
        $this->owner = $owner;

        return $this;
    }

    public function getIsComplete(): null|bool
    {
        return $this->isComplete;
    }

    public function setIsComplete(null|bool $isComplete): self
    {
        $this->isComplete = $isComplete;

        return $this;
    }

    public function getCreatedAt(): DateTimeImmutable|CarbonImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isPublic(): bool
    {
        return $this->isPublic;
    }

    public function setIsPublic(bool $isPublic): void
    {
        $this->isPublic = $isPublic;
    }

    public function getEndAt(): DateTimeImmutable|CarbonImmutable
    {
        return $this->endAt;
    }

    public function setEndAt(DateTimeImmutable $endAt): self
    {
        $this->endAt = $endAt;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getIsTodayAfterEndAt(): bool
    {
        return Carbon::today()->isAfter($this->getCreatedAt());
    }

    public function getIsTodayBeforeEndAt(): bool
    {
        return Carbon::today()->isBefore($this->getCreatedAt());
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

    /**
     * @return Project|null
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * @param Project|null $project
     */
    public function setProject(?Project $project): void
    {
        $this->project = $project;
    }

}
