<?php

namespace App\Entity;

use App\Repository\EventCommentsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventCommentsRepository::class)]
class EventComments
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'eventComments')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?user $user = null;

    #[ORM\ManyToOne(inversedBy: 'eventComments')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?events $event = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $comment_text = null;

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

    public function getCommentText(): ?string
    {
        return $this->comment_text;
    }

    public function setCommentText(?string $comment_text): static
    {
        $this->comment_text = $comment_text;

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
