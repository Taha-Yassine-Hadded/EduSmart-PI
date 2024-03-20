<?php

namespace App\Entity;

use App\Repository\EventsRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventsRepository::class)]
class Events
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(onDelete: 'CASCADE')]
    private ?user $admin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $event_name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $event_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventReactions::class)]
    private Collection $eventReactions;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: EventComments::class)]
    private Collection $eventComments;

    #[ORM\OneToMany(mappedBy: 'event', targetEntity: Notifications::class)]
    private Collection $notifications;

    #[ORM\ManyToMany(targetEntity: user::class, inversedBy: 'event_participant')]
    private Collection $participants;

    #[ORM\Column(length: 255)]
    private ?string $event_photo = null;

    public function __construct()
    {
        $this->eventReactions = new ArrayCollection();
        $this->eventComments = new ArrayCollection();
        $this->notifications = new ArrayCollection();
        $this->participants = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAdmin(): ?user
    {
        return $this->admin;
    }

    public function setAdmin(?user $admin): static
    {
        $this->admin = $admin;

        return $this;
    }

    public function getEventName(): ?string
    {
        return $this->event_name;
    }

    public function setEventName(?string $event_name): static
    {
        $this->event_name = $event_name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getEventDate(): ?\DateTimeInterface
    {
        return $this->event_date;
    }

    public function setEventDate(?\DateTimeInterface $event_date): static
    {
        $this->event_date = $event_date;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * @return Collection<int, EventReactions>
     */
    public function getEventReactions(): Collection
    {
        return $this->eventReactions;
    }

    public function addEventReaction(EventReactions $eventReaction): static
    {
        if (!$this->eventReactions->contains($eventReaction)) {
            $this->eventReactions->add($eventReaction);
            $eventReaction->setEvent($this);
        }

        return $this;
    }

    public function removeEventReaction(EventReactions $eventReaction): static
    {
        if ($this->eventReactions->removeElement($eventReaction)) {
            // set the owning side to null (unless already changed)
            if ($eventReaction->getEvent() === $this) {
                $eventReaction->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, EventComments>
     */
    public function getEventComments(): Collection
    {
        return $this->eventComments;
    }

    public function addEventComment(EventComments $eventComment): static
    {
        if (!$this->eventComments->contains($eventComment)) {
            $this->eventComments->add($eventComment);
            $eventComment->setEvent($this);
        }

        return $this;
    }

    public function removeEventComment(EventComments $eventComment): static
    {
        if ($this->eventComments->removeElement($eventComment)) {
            // set the owning side to null (unless already changed)
            if ($eventComment->getEvent() === $this) {
                $eventComment->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Notifications>
     */
    public function getNotifications(): Collection
    {
        return $this->notifications;
    }

    public function addNotification(Notifications $notification): static
    {
        if (!$this->notifications->contains($notification)) {
            $this->notifications->add($notification);
            $notification->setEvent($this);
        }

        return $this;
    }

    public function removeNotification(Notifications $notification): static
    {
        if ($this->notifications->removeElement($notification)) {
            // set the owning side to null (unless already changed)
            if ($notification->getEvent() === $this) {
                $notification->setEvent(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getParticipants(): Collection
    {
        return $this->participants;
    }

    public function addParticipant(user $participant): static
    {
        if (!$this->participants->contains($participant)) {
            $this->participants->add($participant);
        }

        return $this;
    }

    public function removeParticipant(user $participant): static
    {
        $this->participants->removeElement($participant);

        return $this;
    }

    public function getEventPhoto(): ?string
    {
        return $this->event_photo;
    }

    public function setEventPhoto(string $event_photo): static
    {
        $this->event_photo = $event_photo;

        return $this;
    }
}
