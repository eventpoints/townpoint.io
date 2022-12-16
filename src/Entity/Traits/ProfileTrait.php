<?php

namespace App\Entity\Traits;

use Doctrine\DBAL\Types\Types;

use Doctrine\ORM\Mapping as ORM;

trait ProfileTrait
{

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    public function getDescription(): null|string
    {
        return $this->description;
    }

    public function setDescription(null|string $description): self
    {
        $this->description = $description;

        return $this;
    }

}