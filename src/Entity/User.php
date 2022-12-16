<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Entity\Traits\ProfileTrait;
use App\Enum\ReactionEnum;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use ProfileTrait;

    /**
     * @var Collection<int, Interactor>
     */
    public Collection $friendsWithMe;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private string $password;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column(length: 255)]
    private ?string $gender = null;

    /**
     * @var Collection<int, Conversation> $conversations
     */
    #[ORM\ManyToMany(targetEntity: Conversation::class, mappedBy: 'users')]
    private Collection $conversations;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $avatar = null;

    #[ORM\Column]
    private ?int $age = null;

    /**
     * @var Collection<int, Statement> $statements
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Statement::class)]
    private Collection $statements;

    /**
     * @var Collection<int, Interactor> $interactors
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Interactor::class)]
    private Collection $interactors;

    #[ORM\Column(length: 255)]
    private ?string $countryOfOrigin = null;

    #[ORM\Column(length: 255)]
    private ?string $currentCountry = null;

    #[ORM\Column]
    private ?bool $isEnabled = false;

    /**
     * @var Collection<int, Reaction> $authoredReactions
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Reaction::class)]
    private Collection $authoredReactions;

    /**
     * @var Collection<int, Reaction> $reactions
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Reaction::class)]
    private Collection $reactions;

    /**
     * @var Collection<int, View> $viewed
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: View::class, orphanRemoval: true)]
    private Collection $viewed;

    /**
     * @var Collection<int, View> $views
     */
    #[ORM\OneToMany(mappedBy: 'user', targetEntity: View::class)]
    private Collection $views;

    /**
     * @var Collection<int, Poll> $polls
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Poll::class)]
    private Collection $polls;

    /**
     * @var Collection<int, PollAnswer> $pollAnswers
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: PollAnswer::class)]
    private Collection $pollAnswers;

    /**
     * @var Collection<int, Duration> $durations
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Duration::class)]
    private Collection $durations;

    #[ORM\Column(length: 2)]
    private ?string $language = 'EN';

    #[ORM\Column(length: 3)]
    private ?string $currency = 'EUR';

    #[ORM\Column(length: 255, nullable: true)]
    private null|string $timezone = null;

    /**
     * @var Collection<int, Listener> $listening
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Listener::class)]
    private Collection $listening;

    /**
     * @var Collection<int, Listener> $listeners
     */
    #[ORM\OneToMany(mappedBy: 'target', targetEntity: Listener::class)]
    private Collection $listeners;

    /**
     * @var Collection<int, PhoneNumber> $phoneNumbers
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: PhoneNumber::class)]
    private Collection $phoneNumbers;

    /**
     * @var Collection<int, Address> $addresses
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Address::class)]
    private Collection $addresses;

    #[ORM\Column(length: 255)]
    private ?string $handle = null;

    public function __construct()
    {
        $this->conversations = new ArrayCollection();
        $this->statements = new ArrayCollection();
        $this->interactors = new ArrayCollection();
        $this->friendsWithMe = new ArrayCollection();
        $this->authoredReactions = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->viewed = new ArrayCollection();
        $this->views = new ArrayCollection();
        $this->polls = new ArrayCollection();
        $this->pollAnswers = new ArrayCollection();
        $this->durations = new ArrayCollection();
        $this->listening = new ArrayCollection();
        $this->listeners = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
        $this->addresses = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->getFullName();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
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
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
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

    public function setPassword(string $password): self
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

    public function getFullName(): string
    {
        return $this->firstName . ' ' . $this->lastName;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * @return Collection<int, Conversation>
     */
    public function getConversations(): Collection
    {
        return $this->conversations;
    }

    public function addConversation(Conversation $conversation): self
    {
        if (! $this->conversations->contains($conversation)) {
            $this->conversations->add($conversation);
            $conversation->addUser($this);
        }

        return $this;
    }

    public function removeConversation(Conversation $conversation): self
    {
        if ($this->conversations->removeElement($conversation)) {
            $conversation->removeUser($this);
        }

        return $this;
    }

    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    public function setAvatar(string $avatar): self
    {
        $this->avatar = $avatar;

        return $this;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(int $age): self
    {
        $this->age = $age;

        return $this;
    }

    /**
     * @return Collection<int, Statement>
     */
    public function getStatements(): Collection
    {
        return $this->statements;
    }

    public function addStatement(Statement $statement): self
    {
        if (! $this->statements->contains($statement)) {
            $this->statements->add($statement);
            $statement->setOwner($this);
        }

        return $this;
    }

    public function removeStatement(Statement $statement): self
    {
        // set the owning side to null (unless already changed)
        if ($this->statements->removeElement($statement) && $statement->getOwner() === $this) {
            $statement->setOwner(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Interactor>
     */
    public function getInteractors(): Collection
    {
        return $this->interactors;
    }

    public function addFriend(Interactor $friend): self
    {
        if (! $this->interactors->contains($friend)) {
            $this->interactors->add($friend);
            $friend->setOwner($this);
        }

        return $this;
    }

    public function removeFriend(Interactor $friend): self
    {
        // set the owning side to null (unless already changed)
        if ($this->interactors->removeElement($friend) && $friend->getOwner() === $this) {
            $friend->setOwner(null);
        }

        return $this;
    }

    public function hasFriend(self $user): bool
    {
        /** @var Interactor $friend */
        foreach ($this->interactors as $friend) {
            if ($friend->getUser() === $user && $friend->isIsAccepted()) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return Collection<int, Interactor>
     */
    public function getFriendsWithMe(): Collection
    {
        return $this->friendsWithMe;
    }

    public function addFriendsWithMe(Interactor $friendsWithMe): self
    {
        if (! $this->friendsWithMe->contains($friendsWithMe)) {
            $this->friendsWithMe->add($friendsWithMe);
            $friendsWithMe->setUser($this);
        }

        return $this;
    }

    public function removeFriendsWithMe(Interactor $friendsWithMe): self
    {
        // set the owning side to null (unless already changed)
        if ($this->friendsWithMe->removeElement($friendsWithMe) && $friendsWithMe->getUser() === $this) {
            $friendsWithMe->setUser(null);
        }

        return $this;
    }

    public function getCountryOfOrigin(): ?string
    {
        return $this->countryOfOrigin;
    }

    public function setCountryOfOrigin(string $countryOfOrigin): self
    {
        $this->countryOfOrigin = $countryOfOrigin;

        return $this;
    }

    public function getCurrentCountry(): ?string
    {
        return $this->currentCountry;
    }

    public function setCurrentCountry(string $currentCountry): self
    {
        $this->currentCountry = $currentCountry;

        return $this;
    }

    public function isIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * @return Collection<int, Reaction>
     */
    public function getAuthoredAdmirations(): Collection
    {
        return $this->authoredReactions->filter(function (Reaction $reaction): bool {
            return $reaction->getType() === ReactionEnum::ADMIRE;
        });
    }

    /**
     * @return Collection<int, Reaction>
     */
    public function getAuthoredDissagreements(): Collection
    {
        return $this->authoredReactions->filter(function (Reaction $reaction): bool {
            return $reaction->getType() === ReactionEnum::DISAGREE;
        });
    }

    public function addAuthoredReaction(Reaction $authoredReaction): self
    {
        if (! $this->authoredReactions->contains($authoredReaction)) {
            $this->authoredReactions->add($authoredReaction);
            $authoredReaction->setOwner($this);
        }

        return $this;
    }

    public function removeAuthoredReaction(Reaction $authoredReaction): self
    {
        // set the owning side to null (unless already changed)
        if ($this->authoredReactions->removeElement($authoredReaction) && $authoredReaction->getOwner() === $this) {
            $authoredReaction->setOwner(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Reaction>
     */
    public function getAdmirations(): Collection
    {
        return $this->reactions->filter(function (Reaction $reaction): bool {
            return $reaction->getType() === ReactionEnum::ADMIRE;
        });
    }

    /**
     * @return Collection<int, Reaction>
     */
    public function getDissagreements(): Collection
    {
        return $this->reactions->filter(function (Reaction $reaction): bool {
            return $reaction->getType() === ReactionEnum::DISAGREE;
        });
    }

    public function addReaction(Reaction $reaction): self
    {
        if (! $this->reactions->contains($reaction)) {
            $this->reactions->add($reaction);
            $reaction->setUser($this);
        }

        return $this;
    }

    public function removeReaction(Reaction $reaction): self
    {
        // set the owning side to null (unless already changed)
        if ($this->reactions->removeElement($reaction) && $reaction->getUser() === $this) {
            $reaction->setUser(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, View>
     */
    public function getViewed(): Collection
    {
        return $this->viewed;
    }

    public function addViewed(View $viewed): self
    {
        if (! $this->viewed->contains($viewed)) {
            $this->viewed->add($viewed);
            $viewed->setOwner($this);
        }

        return $this;
    }

    public function removeViewed(View $viewed): self
    {
        // set the owning side to null (unless already changed)
        if ($this->viewed->removeElement($viewed) && $viewed->getOwner() === $this) {
            $viewed->setOwner(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, View>
     */
    public function getViews(): Collection
    {
        return $this->views;
    }

    public function addView(View $view): self
    {
        if (! $this->views->contains($view)) {
            $this->views->add($view);
            $view->setUser($this);
        }

        return $this;
    }

    public function removeView(View $view): self
    {
        // set the owning side to null (unless already changed)
        if ($this->views->removeElement($view) && $view->getUser() === $this) {
            $view->setUser(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Poll>
     */
    public function getPolls(): Collection
    {
        return $this->polls;
    }

    public function addPoll(Poll $poll): self
    {
        if (! $this->polls->contains($poll)) {
            $this->polls->add($poll);
            $poll->setOwner($this);
        }

        return $this;
    }

    public function removePoll(Poll $poll): self
    {
        // set the owning side to null (unless already changed)
        if ($this->polls->removeElement($poll) && $poll->getOwner() === $this) {
            $poll->setOwner(null);
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
            $pollAnswer->setOwner($this);
        }

        return $this;
    }

    public function removePollAnswer(PollAnswer $pollAnswer): self
    {
        // set the owning side to null (unless already changed)
        if ($this->pollAnswers->removeElement($pollAnswer) && $pollAnswer->getOwner() === $this) {
            $pollAnswer->setOwner(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Duration>
     */
    public function getDurations(): Collection
    {
        return $this->durations;
    }

    public function addDuration(Duration $duration): self
    {
        if (! $this->durations->contains($duration)) {
            $this->durations->add($duration);
            $duration->setOwner($this);
        }

        return $this;
    }

    public function removeDuration(Duration $duration): self
    {
        // set the owning side to null (unless already changed)
        if ($this->durations->removeElement($duration) && $duration->getOwner() === $this) {
            $duration->setOwner(null);
        }

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getCurrency(): ?string
    {
        return $this->currency;
    }

    public function setCurrency(string $currency): self
    {
        $this->currency = $currency;

        return $this;
    }

    public function getTimezone(): ?string
    {
        return $this->timezone;
    }

    public function setTimezone(string $timezone): self
    {
        $this->timezone = $timezone;

        return $this;
    }

    /**
     * @return Collection<int, Listener>
     */
    public function getListening(): Collection
    {
        return $this->listening;
    }

    public function addListening(Listener $listening): self
    {
        if (! $this->listening->contains($listening)) {
            $this->listening->add($listening);
            $listening->setOwner($this);
        }

        return $this;
    }

    public function removeListening(Listener $listening): self
    {
        // set the owning side to null (unless already changed)
        if ($listening->getOwner() === $this) {
            $this->listening->removeElement($listening);
        }

        return $this;
    }

    /**
     * @return Collection<int, Listener>
     */
    public function getListeners(): Collection
    {
        return $this->listeners;
    }

    public function addListener(Listener $listener): self
    {
        if (! $this->listeners->contains($listener)) {
            $this->listeners->add($listener);
            $listener->setTarget($this);
        }

        return $this;
    }

    public function removeListener(Listener $listener): self
    {
        // set the owning side to null (unless already changed)
        if ($listener->getTarget() === $this) {
            $this->listeners->removeElement($listener);
        }

        return $this;
    }

    /**
     * @return Collection<int, PhoneNumber>
     */
    public function getPhoneNumbers(): Collection
    {
        return $this->phoneNumbers;
    }

    public function addPhoneNumber(PhoneNumber $phoneNumber): self
    {
        if (! $this->phoneNumbers->contains($phoneNumber)) {
            $this->phoneNumbers->add($phoneNumber);
            $phoneNumber->setOwner($this);
        }

        return $this;
    }

    public function removePhoneNumber(PhoneNumber $phoneNumber): self
    {
        // set the owning side to null (unless already changed)
        if ($phoneNumber->getOwner() === $this) {
            $this->phoneNumbers->removeElement($phoneNumber);
        }

        return $this;
    }

    public function getIsListening(self $user): bool
    {
        return $this->listening->exists(function ($key, Listener $element) use ($user): bool {
            return $user->getId() === $element->getTarget()
                ->getId();
        });
    }

    /**
     * @return Collection<int, Address>
     */
    public function getAddresses(): Collection
    {
        return $this->addresses;
    }

    public function addAddress(Address $address): self
    {
        if (! $this->addresses->contains($address)) {
            $this->addresses->add($address);
            $address->setOwner($this);
        }

        return $this;
    }

    public function removeAddress(Address $address): self
    {
        // set the owning side to null (unless already changed)
        if ($this->addresses->removeElement($address) && $address->getOwner() === $this) {
            $address->setOwner(null);
        }

        return $this;
    }

    public function getHandle(): ?string
    {
        return $this->handle;
    }

    public function setHandle(string $handle): self
    {
        $this->handle = $handle;

        return $this;
    }
}
