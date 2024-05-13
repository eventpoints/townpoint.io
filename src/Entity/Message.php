<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column]
    private ?DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, MessageRead>
     */
    #[ORM\OneToMany(mappedBy: 'message', targetEntity: MessageRead::class, cascade: ['persist'])]
    private Collection $messageReads;

    public function __construct(
        #[ORM\Column(type: Types::TEXT)]
        private ?string $content = null,
        #[ORM\ManyToOne]
        private ?User $owner = null,
        #[ORM\ManyToOne(inversedBy: 'messages')]
        #[ORM\JoinColumn(nullable: false)]
        private ?Conversation $conversation = null
    ) {
        $this->createdAt = new DateTimeImmutable();
        $this->messageReads = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(string $content): static
    {
        $this->content = $content;

        return $this;
    }

    public function getCreatedAt(): ?DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        $this->owner = $owner;

        return $this;
    }

    public function getConversation(): ?Conversation
    {
        return $this->conversation;
    }

    public function setConversation(?Conversation $conversation): static
    {
        $this->conversation = $conversation;

        return $this;
    }

    /**
     * @return Collection<int, MessageRead>
     */
    public function getMessageReads(): Collection
    {
        return $this->messageReads;
    }

    public function addMessageRead(MessageRead $messageRead): static
    {
        if (! $this->messageReads->contains($messageRead)) {
            $this->messageReads->add($messageRead);
            $messageRead->setMessage($this);
        }

        return $this;
    }

    public function removeMessageRead(MessageRead $messageRead): static
    {
        // set the owning side to null (unless already changed)
        if ($this->messageReads->removeElement($messageRead) && $messageRead->getMessage() === $this) {
            $messageRead->setMessage(null);
        }

        return $this;
    }

    public function getIsRead(User $user): bool
    {
        return $this->getMessageReads()->exists(fn (int $key, MessageRead $read): bool => $read->getOwner() === $user);
    }
}
