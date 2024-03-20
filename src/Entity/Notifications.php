<?php

namespace App\Entity;

use App\Repository\NotificationsRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: NotificationsRepository::class)]
class Notifications
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    private ?user $user = null;

    #[ORM\ManyToOne(inversedBy: 'notifications')]
    private ?events $event = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $notification_text = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $notification_time = null;

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

    public function getNotificationText(): ?string
    {
        return $this->notification_text;
    }

    public function setNotificationText(string $notification_text): static
    {
        $this->notification_text = $notification_text;

        return $this;
    }

    public function getNotificationTime(): ?\DateTimeInterface
    {
        return $this->notification_time;
    }

    public function setNotificationTime(\DateTimeInterface $notification_time): static
    {
        $this->notification_time = $notification_time;

        return $this;
    }
}
