<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Entity\Business\Business;
use App\Entity\Event\Event;
use App\Entity\Event\EventInvite;
use App\Entity\Event\EventRejection;
use App\Entity\Event\EventRequest;
use App\Entity\Event\EventParticipant;
use App\Entity\Group\Group;
use App\Entity\Group\GroupRequest;
use App\Entity\Market\Classified;
use App\Entity\Market\Item;
use App\Entity\Traits\ProfileTrait;
use App\Enum\ReactionEnum;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ReadableCollection;
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
#[UniqueEntity(fields: ['handle'], message: 'This handle is taken')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    use ProfileTrait;

    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[Assert\NotBlank]
    #[Assert\Email( message: 'The email {{ value }} is not a valid email.')]
    #[ORM\Column(length: 180, unique: true)]
    private string $email;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[Assert\NotCompromisedPassword]
    private string $password;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::STRING)]
    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[Assert\NotBlank]
    #[Assert\Type(type: Types::STRING)]
    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[Assert\NotBlank]
    #[Assert\Choice(choices: ['male', 'female'])]
    #[ORM\Column(length: 255)]
    private ?string $gender = null;

    /**
     * @var Collection<int, Conversation> $conversations
     */
    #[ORM\ManyToMany(targetEntity: Conversation::class, mappedBy: 'users')]
    private Collection $conversations;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $avatar = null;

    #[Assert\NotBlank]
    #[Assert\Positive]
    #[Assert\LessThan(125)]
    #[ORM\Column]
    private ?int $age = null;

    #[Assert\NotBlank]
    #[Assert\Country]
    #[ORM\Column(length: 255)]
    private ?string $countryOfOrigin = null;

    #[Assert\NotBlank]
    #[Assert\Country]
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

    #[Assert\NotBlank]
    #[Assert\Language]
    #[ORM\Column(length: 2)]
    private ?string $language = 'en';

    #[Assert\NotBlank]
    #[Assert\Currency]
    #[ORM\Column(length: 3)]
    private ?string $currency = 'EUR';

    #[Assert\NotBlank]
    #[Assert\Timezone]
    #[ORM\Column(length: 255, nullable: true)]
    private null|string $timezone = 'UTC';

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

    #[ORM\ManyToOne(targetEntity: PhoneNumber::class, inversedBy: 'owner')]
    private null|PhoneNumber $phoneNumber = null;

    #[ORM\ManyToOne(targetEntity: Address::class, inversedBy: 'owner')]
    private null|Address $address = null;

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

    #[Assert\NotBlank]
    #[Assert\Regex(pattern: '/^[a-z\d_.]{5,20}$/i')]
    #[ORM\Column(length: 255, unique: true)]
    private string $handle;

    /**
     * @var Collection<int, Snippet>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Snippet::class)]
    #[ORM\OrderBy([
        'createdAt' => 'DESC',
    ])]
    private Collection $snippets;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Event::class)]
    private Collection $authoredEvents;

    /**
     * @var Collection<int, EventParticipant>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: EventParticipant::class)]
    private Collection $eventParticipants;

    /**
     * @var Collection<int, EventRequest>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: EventRequest::class)]
    private Collection $eventRequests;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Comment::class)]
    private Collection $comments;

    /**
     * @var Collection<int, Business>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Business::class)]
    private Collection $businesses;

    /**
     * @var Collection<int, EventInvite>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: EventInvite::class, orphanRemoval: true)]
    private Collection $eventInvites;

    /**
     * @var Collection<int, GroupRequest>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: GroupRequest::class, orphanRemoval: true)]
    private Collection $groupRequests;

    /**
     * @var Collection<int, Group>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Group::class)]
    private Collection $ownedGroups;

    /**
     * @var Collection<int, EventRejection>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: EventRejection::class, orphanRemoval: true)]
    private Collection $eventRejections;

    #[ORM\Column]
    private null|bool $isVisible = true;

    #[ORM\Column]
    private null|bool $isSuspended = false;

    /**
     * @var Collection<int, Bookmark>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Bookmark::class)]
    private Collection $bookmarks;

    /**
     * @var Collection<int, Comment>
     */
    #[ORM\OneToMany(mappedBy: 'post', targetEntity: Comment::class)]
    private Collection $posts;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Project::class)]
    private Collection $projects;

    #[ORM\OneToOne(cascade: ['persist', 'remove'])]
    private null|Project $currentProject = null;

    /**
     * @var Collection<int, Classified>
     */
    #[ORM\OneToMany(mappedBy: 'owner', targetEntity: Classified::class, orphanRemoval: true)]
    private Collection $classifieds;

    public function __construct()
    {
        $this->conversations = new ArrayCollection();
        $this->authoredReactions = new ArrayCollection();
        $this->reactions = new ArrayCollection();
        $this->listening = new ArrayCollection();
        $this->listeners = new ArrayCollection();
        $this->phoneNumbers = new ArrayCollection();
        $this->addresses = new ArrayCollection();
        $this->snippets = new ArrayCollection();
        $this->authoredEvents = new ArrayCollection();
        $this->eventParticipants = new ArrayCollection();
        $this->eventRequests = new ArrayCollection();
        $this->comments = new ArrayCollection();
        $this->businesses = new ArrayCollection();
        $this->eventInvites = new ArrayCollection();
        $this->groupRequests = new ArrayCollection();
        $this->ownedGroups = new ArrayCollection();
        $this->eventRejections = new ArrayCollection();
        $this->bookmarks = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->projects = new ArrayCollection();
        $this->classifieds = new ArrayCollection();
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

    public function getIsEnabled(): ?bool
    {
        return $this->isEnabled;
    }

    public function setIsEnabled(bool $isEnabled): self
    {
        $this->isEnabled = $isEnabled;

        return $this;
    }

    /**
     * @return ReadableCollection<int, Reaction>
     */
    public function getAuthoredAdmirations(): ReadableCollection
    {
        return $this->authoredReactions->filter(function (Reaction $reaction): bool {
            return $reaction->getType() === ReactionEnum::ADMIRE;
        });
    }

    /**
     * @return ReadableCollection<int, Reaction>
     */
    public function getAuthoredDissagreements(): ReadableCollection
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
     * @return ReadableCollection<int, Reaction>
     */
    public function getAdmirations(): ReadableCollection
    {
        return $this->reactions->filter(function (Reaction $reaction): bool {
            return $reaction->getType() === ReactionEnum::ADMIRE;
        });
    }

    /**
     * @return ReadableCollection<int, Reaction>
     */
    public function getDissagreements(): ReadableCollection
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

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function setHandle(string $handle): self
    {
        $this->handle = $handle;

        return $this;
    }

    /**
     * @return Collection<int, Snippet>
     */
    public function getSnippets(): Collection
    {
        return $this->snippets;
    }

    public function addSnippet(Snippet $snippet): self
    {
        if (! $this->snippets->contains($snippet)) {
            $this->snippets->add($snippet);
            $snippet->setOwner($this);
        }

        return $this;
    }

    public function removeSnippet(Snippet $snippet): self
    {
        // set the owning side to null (unless already changed)
        if ($this->snippets->removeElement($snippet) && $snippet->getOwner() === $this) {
            $snippet->setOwner(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getAuthoredEvents(): Collection
    {
        return $this->authoredEvents;
    }

    public function addAuthoredEvent(Event $authoredEvent): self
    {
        if (! $this->authoredEvents->contains($authoredEvent)) {
            $this->authoredEvents->add($authoredEvent);
            $authoredEvent->setOwner($this);
        }

        return $this;
    }

    public function removeAuthoredEvent(Event $authoredEvent): self
    {
        // set the owning side to null (unless already changed)
        $this->authoredEvents->removeElement($authoredEvent);

        return $this;
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
            $eventRequest->setOwner($this);
        }

        return $this;
    }

    public function removeEventRequest(EventRequest $eventRequest): self
    {
        // set the owning side to null (unless already changed)
        $this->eventRequests->removeElement($eventRequest);

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
            $comment->setOwner($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): self
    {
        // set the owning side to null (unless already changed)
        if ($comment->getOwner() === $this) {
            $this->comments->removeElement($comment);
        }

        return $this;
    }

    /**
     * @return Collection<int, Business>
     */
    public function getBusinesses(): Collection
    {
        return $this->businesses;
    }

    public function addBusiness(Business $business): self
    {
        if (! $this->businesses->contains($business)) {
            $this->businesses->add($business);
            $business->setOwner($this);
        }

        return $this;
    }

    public function removeBusiness(Business $business): self
    {
        // set the owning side to null (unless already changed)
        if ($business->getOwner() === $this) {
            $this->businesses->removeElement($business);
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
            $eventInvite->setOwner($this);
        }

        return $this;
    }

    public function removeEventInvite(EventInvite $eventInvite): self
    {
        // set the owning side to null (unless already changed)
        if ($eventInvite->getOwner() === $this) {
            $this->eventInvites->removeElement($eventInvite);
        }

        return $this;
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
            $groupRequest->setOwner($this);
        }

        return $this;
    }

    public function removeGroupRequest(GroupRequest $groupRequest): self
    {
        // set the owning side to null (unless already changed)
        if ($groupRequest->getOwner() === $this) {
            $this->groupRequests->removeElement($groupRequest);
        }

        return $this;
    }

    /**
     * @return Collection<int, Group>
     */
    public function getOwnedGroups(): Collection
    {
        return $this->ownedGroups;
    }

    public function addOwnedGroup(Group $ownedGroup): self
    {
        if (! $this->ownedGroups->contains($ownedGroup)) {
            $this->ownedGroups->add($ownedGroup);
            $ownedGroup->setOwner($this);
        }

        return $this;
    }

    public function removeOwnedGroup(Group $ownedGroup): self
    {
        // set the owning side to null (unless already changed)
        if ($this->ownedGroups->removeElement($ownedGroup) && $ownedGroup->getOwner() === $this) {
            $ownedGroup->setOwner(null);
        }

        return $this;
    }

    public function getPhoneNumber(): ?PhoneNumber
    {
        return $this->phoneNumber;
    }

    public function setPhoneNumber(?PhoneNumber $phoneNumber): void
    {
        $this->phoneNumber = $phoneNumber;
    }

    public function getAddress(): ?Address
    {
        return $this->address;
    }

    public function setAddress(?Address $address): void
    {
        $this->address = $address;
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
            $eventRejection->setOwner($this);
        }

        return $this;
    }

    public function removeEventRejection(EventRejection $eventRejection): self
    {
        // set the owning side to null (unless already changed)
        if ($this->eventRejections->removeElement($eventRejection) && $eventRejection->getOwner() === $this) {
            $eventRejection->setOwner(null);
        }

        return $this;
    }

    public function getIsVisible(): ?bool
    {
        return $this->isVisible;
    }

    public function setIsVisible(bool $isVisible): self
    {
        $this->isVisible = $isVisible;

        return $this;
    }

    public function isIsSuspended(): ?bool
    {
        return $this->isSuspended;
    }

    public function setIsSuspended(bool $isSuspended): self
    {
        $this->isSuspended = $isSuspended;

        return $this;
    }

    /**
     * @return Collection<int, Bookmark>
     */
    public function getBookmarks(): Collection
    {
        return $this->bookmarks;
    }

    public function addBookmark(Bookmark $bookmark): self
    {
        if (! $this->bookmarks->contains($bookmark)) {
            $this->bookmarks->add($bookmark);
            $bookmark->setOwner($this);
        }

        return $this;
    }

    public function removeBookmark(Bookmark $bookmark): self
    {
        // set the owning side to null (unless already changed)
        if ($this->bookmarks->removeElement($bookmark) && $bookmark->getOwner() === $this) {
            $bookmark->setOwner(null);
        }

        return $this;
    }

    public function hasItemBeenBookmarked(Item $item): bool
    {
        return $this->getBookmarks()
            ->exists(function (int $key, Bookmark $bookmark) use ($item): bool {
                return $bookmark->getItem() === $item;
            });
    }

    /**
     * @return Collection<int, Comment>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Comment $post): self
    {
        if (! $this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setPost($this);
        }

        return $this;
    }

    public function removePost(Comment $post): self
    {
        // set the owning side to null (unless already changed)
        if ($this->posts->removeElement($post) && $post->getPost() === $this) {
            $post->setPost(null);
        }

        return $this;
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    public function addProject(Project $project): self
    {
        if (! $this->projects->contains($project)) {
            $this->projects->add($project);
            $project->setOwner($this);
        }

        return $this;
    }

    public function removeProject(Project $project): self
    {
        // set the owning side to null (unless already changed)
        if ($this->projects->removeElement($project) && $project->getOwner() === $this) {
            $project->setOwner(null);
        }

        return $this;
    }

    public function getCurrentProject(): null|Project
    {
        return $this->currentProject;
    }

    public function setCurrentProject(null|Project $currentProject): self
    {
        $this->currentProject = $currentProject;

        return $this;
    }

    /**
     * @return Collection<int, Classified>
     */
    public function getClassifieds(): Collection
    {
        return $this->classifieds;
    }

    public function addClassified(Classified $classified): self
    {
        if (!$this->classifieds->contains($classified)) {
            $this->classifieds->add($classified);
            $classified->setOwner($this);
        }

        return $this;
    }

    public function removeClassified(Classified $classified): self
    {
        if ($this->classifieds->removeElement($classified)) {
            // set the owning side to null (unless already changed)
            if ($classified->getOwner() === $this) {
                $classified->setOwner(null);
            }
        }

        return $this;
    }


    /**
     * @return Collection<int, EventParticipant>
     */
    public function getEventParticipants(): Collection
    {
        return $this->eventParticipants;
    }

    public function addEventParticipant(EventParticipant $eventUser): self
    {
        if (! $this->eventParticipants->contains($eventUser)) {
            $this->eventParticipants->add($eventUser);
            $eventUser->setOwner($this);
        }

        return $this;
    }

    public function removeEventParticipant(EventParticipant $eventUser): self
    {
        // set the owning side to null (unless already changed)
        if ($eventUser->getOwner() === $this) {
            $this->eventParticipants->removeElement($eventUser);
        }

        return $this;
    }
}
