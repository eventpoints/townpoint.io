<?php

namespace App\Entity;

use App\Repository\TownRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: TownRepository::class)]
class Town implements \Stringable
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column]
    private DateTimeImmutable $createdAt;

    /**
     * @var Collection<int, Statement> $statements
     */
    #[ORM\OneToMany(mappedBy: 'town', targetEntity: Statement::class)]
    private Collection $statements;

    public function __construct(
        #[ORM\Column(length: 255)]
        private string $name,
        #[ORM\Column(length: 255)]
        private string $slug,
        #[ORM\Column]
        private float $latitude,
        #[ORM\Column]
        private float $longitude,
        #[ORM\ManyToOne(inversedBy: 'cities')]
        private Country $country,
    ) {
        $this->createdAt = new DateTimeImmutable();
        $this->statements = new ArrayCollection();
    }

    public function getId(): Uuid
    {
        return $this->id;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(DateTimeImmutable $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getLatitude(): float
    {
        return $this->latitude;
    }

    public function setLatitude(float $latitude): void
    {
        $this->latitude = $latitude;
    }

    public function getLongitude(): float
    {
        return $this->longitude;
    }

    public function setLongitude(float $longitude): void
    {
        $this->longitude = $longitude;
    }

    public function getCountry(): Country
    {
        return $this->country;
    }

    public function setCountry(Country $country): void
    {
        $this->country = $country;
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
        if (! $this->statements->contains($statement)) {
            $this->statements->add($statement);
            $statement->setTown($this);
        }

        return $this;
    }

    public function removeStatement(Statement $statement): static
    {
        // set the owning side to null (unless already changed)
        if ($this->statements->removeElement($statement) && $statement->getTown() === $this) {
            $statement->setTown(null);
        }

        return $this;
    }

    public function __toString(): string
    {
        return $this->getCountry()->getName() . ', ' .
            $this->getName();
    }
}
