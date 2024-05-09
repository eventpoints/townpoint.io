<?php

namespace App\Entity;

use App\Enum\ContinentEnum;
use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\Order;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    /**
     * @var Collection<int, Town> $towns
     */
    #[ORM\OneToMany(mappedBy: 'country', targetEntity: Town::class, cascade: ['persist'])]
    #[ORM\OrderBy([
        "name" => Order::Ascending->value,
    ])]
    private Collection $towns;

    public function __construct(
        #[ORM\Column(length: 255)]
        private ?string $name,
        #[ORM\Column(length: 255)]
        private string $slug,
        #[ORM\Column(length: 2)]
        private ?string $alpha2 = null,
        #[ORM\Column(length: 3)]
        private ?string $alpha3 = null,
        #[ORM\Column(type: Types::INTEGER, length: 3)]
        private ?string $num = null,
        #[ORM\Column(type: Types::INTEGER, length: 10)]
        private ?string $isd = null,
        #[ORM\Column(type: Types::STRING, enumType: ContinentEnum::class)]
        private null|ContinentEnum $continent = null,
        #[ORM\ManyToOne]
        private null|Town $capitalCity = null
    ) {
        $this->towns = new ArrayCollection();
    }

    public function getId(): null|Uuid
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getSlug(): string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): void
    {
        $this->slug = $slug;
    }

    public function getAlpha2(): ?string
    {
        return $this->alpha2;
    }

    public function setAlpha2(?string $alpha2): void
    {
        $this->alpha2 = $alpha2;
    }

    public function getAlpha3(): ?string
    {
        return $this->alpha3;
    }

    public function setAlpha3(?string $alpha3): void
    {
        $this->alpha3 = $alpha3;
    }

    public function getNum(): ?string
    {
        return $this->num;
    }

    public function setNum(?string $num): void
    {
        $this->num = $num;
    }

    public function getIsd(): ?string
    {
        return $this->isd;
    }

    public function setIsd(?string $isd): void
    {
        $this->isd = $isd;
    }

    public function getContinent(): ?ContinentEnum
    {
        return $this->continent;
    }

    public function setContinent(?ContinentEnum $continent): void
    {
        $this->continent = $continent;
    }

    public function getCapitalCity(): ?Town
    {
        return $this->capitalCity;
    }

    public function setCapitalCity(?Town $capitalCity): void
    {
        $this->capitalCity = $capitalCity;
    }

    /**
     * @return Collection<int, Town>
     */
    public function getTowns(): Collection
    {
        return $this->towns;
    }

    public function addTown(Town $town): static
    {
        if (! $this->towns->contains($town)) {
            $this->towns->add($town);
            $town->setCountry($this);
        }

        return $this;
    }

    public function removeTown(Town $city): static
    {
        $this->towns->removeElement($city);
        return $this;
    }
}
