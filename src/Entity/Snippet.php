<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Repository\SnippetRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: SnippetRepository::class)]
class Snippet
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(type: Types::TEXT)]
    private string $content;

    #[ORM\ManyToOne(inversedBy: 'snippets')]
    private ?User $owner = null;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, Snippet>
     */
    #[ORM\OneToMany(mappedBy: 'snippet', targetEntity: self::class)]
    private Collection $snippets;

    #[ORM\ManyToOne(targetEntity: self::class, inversedBy: 'snippets')]
    private Snippet|null $snippet = null;

    public function __construct()
    {
        $this->createdAt = new DateTimeImmutable();
        $this->snippets = new ArrayCollection();
    }

    public function getId(): Uuid
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

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
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
     * @return Collection<int, Snippet>
     */
    public function getSnippets(): Collection
    {
        return $this->snippets;
    }

    public function addSnippet(self $snippet): self
    {
        if (! $this->snippets->contains($snippet)) {
            $this->snippets->add($snippet);
        }

        return $this;
    }

    public function removeSnippet(self $snippet): self
    {
        $this->snippets->removeElement($snippet);

        return $this;
    }

    public function getSnippet(): ?self
    {
        return $this->snippet;
    }

    public function setSnippet(?self $snippet): void
    {
        $this->snippet = $snippet;
    }
}
