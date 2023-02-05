<?php

namespace App\Entity\Group;

use App\Entity\Event\Event;
use App\Repository\Group\GroupEventRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: GroupEventRepository::class)]
class GroupEvent
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToOne(mappedBy: 'groupEvent', targetEntity: Event::class)]
    #[ORM\JoinColumn(nullable: false)]
    private null|Event $event = null;

    #[ORM\ManyToOne(targetEntity: Group::class, inversedBy: 'groupEvent')]
    #[ORM\JoinColumn(nullable: false)]
    private null|Group $group = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEvent(): ?Event
    {
        return $this->event;
    }

    public function setEvent(?Event $event): self
    {
        $this->event = $event;

        return $this;
    }

    public function getGroup(): ?Group
    {
        return $this->group;
    }

    public function setGroup(?Group $group): self
    {
        $this->group = $group;

        return $this;
    }
}
