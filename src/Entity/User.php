<?php

namespace App\Entity;

use App\Enum\GenderEnum;
use App\Repository\UserRepository;
use Carbon\CarbonImmutable;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface, \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\Email]
    private ?string $email = null;

    #[ORM\Column(length: 180, nullable: true)]
    private null|string $handle = null;

    /**
     * @var string[]
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\PasswordStrength]
    private ?string $password = null;

    #[ORM\Column(type: 'boolean')]
    private bool $isVerified = false;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $autobio = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    private ?string $lastName = null;

    #[ORM\Column(enumType: GenderEnum::class,nullable: true)]
    private null|GenderEnum $gender = null;

    #[ORM\Column(length: 255, nullable: true)]
    private null|string $originCountry = null;

    #[ORM\Column(length: 255, nullable: true)]
    private null|string $currentCountry = null;

    /**
     * @var Collection<int, Statement>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Statement::class)]
    private Collection $statements;

    /**
     * @var Collection<int, ConversationParticipant>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: ConversationParticipant::class)]
    private Collection $conversationParticipants;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private null|CarbonImmutable $lastActiveAt = null;

    /**
     * @var Collection<int, ProfileView>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: ProfileView::class)]
    private Collection $authouredViews;

    /**
     * @var Collection<int, ProfileView>
     */
    #[ORM\OneToMany(mappedBy: 'target', targetEntity: ProfileView::class)]
    private Collection $viewsRecived;

    /**
     * @var Collection<int, MessageRead>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: MessageRead::class)]
    private Collection $messageReads;

    #[ORM\OneToOne(inversedBy: 'owner', cascade: ['persist', 'remove'])]
    private ?ApplicationConfiguration $applicationConfiguration = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private null|CarbonImmutable $createdAt = null;


    #[ORM\Column(type: Types::DATETIME_IMMUTABLE, nullable: true)]
    private null|DateTimeImmutable $bornAt = null;

    public function __construct()
    {
        $this->statements = new ArrayCollection();
        $this->conversationParticipants = new ArrayCollection();
        $this->lastActiveAt = new CarbonImmutable();
        $this->authouredViews = new ArrayCollection();
        $this->viewsRecived = new ArrayCollection();
        $this->messageReads = new ArrayCollection();
        $this->createdAt = new CarbonImmutable();
    }

    public function getId(): null|Uuid
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string)$this->email;
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     * @return $this
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): static
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAutobio(): ?string
    {
        return $this->autobio;
    }

    public function setAutobio(?string $autobio): static
    {
        $this->autobio = $autobio;

        return $this;
    }

    public function __toString(): string
    {
        return (string)$this->getEmail();
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getLastName();
    }

    public function getOriginCountry(): null|string
    {
        return $this->originCountry;
    }

    public function setOriginCountry(null|string $originCountry): void
    {
        $this->originCountry = $originCountry;
    }

    public function getCurrentCountry(): null|string
    {
        return $this->currentCountry;
    }

    public function setCurrentCountry(null|string $currentCountry): void
    {
        $this->currentCountry = $currentCountry;
    }

    /**
     * @return Collection<int, Statement>
     */
    public function getStatements(): Collection
    {
        return $this->statements;
    }

    public function addStatement(Statement $statement): static
    {
        if (!$this->statements->contains($statement)) {
            $this->statements->add($statement);
            $statement->setOwner($this);
        }

        return $this;
    }

    public function removeStatement(Statement $statement): static
    {
        // set the owning side to null (unless already changed)
        if ($this->statements->removeElement($statement) && $statement->getOwner() === $this) {
            $statement->setOwner(null);
        }

        return $this;
    }

    public function getHandle(): null|string
    {
        return $this->handle;
    }

    public function setHandle(string $handle): void
    {
        $this->handle = $handle;
    }

    /**
     * @return Collection<int, ConversationParticipant>
     */
    public function getConversationParticipants(): Collection
    {
        return $this->conversationParticipants;
    }

    public function addConversationParticipant(ConversationParticipant $conversationParticipant): static
    {
        if (!$this->conversationParticipants->contains($conversationParticipant)) {
            $this->conversationParticipants->add($conversationParticipant);
            $conversationParticipant->setOwner($this);
        }

        return $this;
    }

    public function removeConversationParticipant(ConversationParticipant $conversationParticipant): static
    {
        // set the owning side to null (unless already changed)
        if ($this->conversationParticipants->removeElement($conversationParticipant) && $conversationParticipant->getOwner() === $this) {
            $conversationParticipant->setOwner(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, null|Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversationParticipants->map(fn(ConversationParticipant $conversationParticipant): null|Conversation => $conversationParticipant->getConversation());
    }

    public function getLastActiveAt(): null|CarbonImmutable
    {
        return $this->lastActiveAt;
    }

    public function setLastActiveAt(CarbonImmutable $lastActiveAt): static
    {
        $this->lastActiveAt = $lastActiveAt;

        return $this;
    }

    /**
     * @return Collection<int, ProfileView>
     */
    public function getAuthouredViews(): Collection
    {
        return $this->authouredViews;
    }

    public function addAuthouredView(ProfileView $authouredView): static
    {
        if (!$this->authouredViews->contains($authouredView)) {
            $this->authouredViews->add($authouredView);
            $authouredView->setOwner($this);
        }

        return $this;
    }

    public function removeAuthouredView(ProfileView $authouredView): static
    {
        // set the owning side to null (unless already changed)
        if ($this->authouredViews->removeElement($authouredView) && $authouredView->getOwner() === $this) {
            $authouredView->setOwner(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, ProfileView>
     */
    public function getViewsRecived(): Collection
    {
        return $this->viewsRecived;
    }

    public function addViewsRecived(ProfileView $viewsRecived): static
    {
        if (!$this->viewsRecived->contains($viewsRecived)) {
            $this->viewsRecived->add($viewsRecived);
            $viewsRecived->setTarget($this);
        }

        return $this;
    }

    public function removeViewsRecived(ProfileView $viewsRecived): static
    {
        // set the owning side to null (unless already changed)
        if ($this->viewsRecived->removeElement($viewsRecived) && $viewsRecived->getTarget() === $this) {
            $viewsRecived->setTarget(null);
        }

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
        if (!$this->messageReads->contains($messageRead)) {
            $this->messageReads->add($messageRead);
            $messageRead->setOwner($this);
        }

        return $this;
    }

    public function removeMessageRead(MessageRead $messageRead): static
    {
        // set the owning side to null (unless already changed)
        if ($this->messageReads->removeElement($messageRead) && $messageRead->getOwner() === $this) {
            $messageRead->setOwner(null);
        }

        return $this;
    }

    public function getApplicationConfiguration(): ?ApplicationConfiguration
    {
        return $this->applicationConfiguration;
    }

    public function setApplicationConfiguration(?ApplicationConfiguration $applicationConfiguration): static
    {
        $this->applicationConfiguration = $applicationConfiguration;

        return $this;
    }

    public function getCreatedAt(): null|CarbonImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(null|CarbonImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getGender(): ?GenderEnum
    {
        return $this->gender;
    }

    public function setGender(?GenderEnum $gender): void
    {
        $this->gender = $gender;
    }

    public function getBornAt(): ?DateTimeImmutable
    {
        return $this->bornAt;
    }

    public function getAge(): null|int
    {
        return CarbonImmutable::parse($this->bornAt)->diffInYears();
    }

    public function setBornAt(?DateTimeImmutable $bornAt): void
    {
        $this->bornAt = $bornAt;
    }

}
