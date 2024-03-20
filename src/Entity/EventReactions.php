<?php

namespace App\Entity;

use App\Repository\EventReactionsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventReactionsRepository::class)]
class EventReactions
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'eventReactions')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?user $user = null;

    #[ORM\ManyToOne(inversedBy: 'eventReactions')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?events $event = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $reaction_type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?user
    {
        return $this->user;
    }

    public function setUser(?user $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getEvent(): ?events
    {
        return $this->event;
    }

    public function setEvent(?events $event): static
    {
        $this->event = $event;

        return $this;
    }

    public function getReactionType(): ?string
    {
        return $this->reaction_type;
    }

    public function setReactionType(?string $reaction_type): static
    {
        $this->reaction_type = $reaction_type;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }
}
