<?php

namespace App\Entity;

use App\Repository\ApplicationConfigurationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\IdGenerator\UuidGenerator;
use Symfony\Component\Uid\Uuid;

#[ORM\Entity(repositoryClass: ApplicationConfigurationRepository::class)]
class ApplicationConfiguration
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'CUSTOM')]
    #[ORM\Column(type: 'uuid', unique: true)]
    #[ORM\CustomIdGenerator(UuidGenerator::class)]
    private Uuid $id;

    #[ORM\Column(length: 255)]
    private ?string $theme = null;

    #[ORM\OneToOne(mappedBy: 'applicationConfiguration', cascade: ['persist', 'remove'])]
    private ?User $owner = null;

    public function getId(): null|Uuid
    {
        return $this->id;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(string $theme): static
    {
        $this->theme = $theme;

        return $this;
    }

    public function getOwner(): ?User
    {
        return $this->owner;
    }

    public function setOwner(?User $owner): static
    {
        // unset the owning side of the relation if necessary
        if (! $owner instanceof \App\Entity\User && $this->owner instanceof \App\Entity\User) {
            $this->owner->setApplicationConfiguration(null);
        }

        // set the owning side of the relation if necessary
        if ($owner instanceof \App\Entity\User && $owner->getApplicationConfiguration() !== $this) {
            $owner->setApplicationConfiguration($this);
        }

        $this->owner = $owner;

        return $this;
    }
}
