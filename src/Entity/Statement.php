<?php

namespace App\Entity;

use App\Enum\StatementTypeEnum;
use App\Repository\StatementRepository;
use Carbon\CarbonImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: StatementRepository::class)]
class Statement
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(enumType: StatementTypeEnum::class)]
    private StatementTypeEnum|null $type = null;

    #[ORM\Column(type: Types::DATETIME_IMMUTABLE)]
    private CarbonImmutable|null $createdAt = null;

    /**
     * One Category has Many Categories.
     * @var Collection<int, Statement>
     */
    #[ORM\OneToMany(mappedBy: 'statement', targetEntity: Statement::class, cascade: ['persist'])]
    private Collection $statements;

    public function __construct(
        #[ORM\Column(type: Types::TEXT)]
        private ?string $content = null,
        #[ORM\ManyToOne(inversedBy: 'statements')]
        private ?User $owner = null,
        #[ORM\ManyToOne(inversedBy: 'statements')]
        private ?Town $town = null,
        #[ORM\ManyToOne(targetEntity: Statement::class, inversedBy: 'statements')]
        #[ORM\JoinColumn(name: 'statement_id', referencedColumnName: 'id')]
        private Statement|null $statement = null
    ) {
        $this->statements = new ArrayCollection();
        $this->createdAt = new CarbonImmutable();
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

    public function getCreatedAt(): ?CarbonImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(CarbonImmutable $createdAt): static
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

    public function getTown(): ?Town
    {
        return $this->town;
    }

    public function setTown(?Town $town): static
    {
        $this->town = $town;

        return $this;
    }

    public function getType(): ?StatementTypeEnum
    {
        return $this->type;
    }

    public function setType(?StatementTypeEnum $type): void
    {
        $this->type = $type;
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
            $statement->setStatement($this);
        }

        return $this;
    }

    public function removeStatement(Statement $statement): static
    {
        if ($this->statements->removeElement($statement) && $statement->getStatement() === $this) {
            $statement->setStatement(null);
        }

        return $this;
    }

    public function getStatement(): null|Statement
    {
        return $this->statement;
    }

    public function setStatement(null|Statement $statement): void
    {
        $this->statement = $statement;
    }
}
