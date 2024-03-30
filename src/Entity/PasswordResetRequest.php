<?php

namespace App\Entity;

use App\Repository\PasswordResetRequestRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PasswordResetRequestRepository::class)]
class PasswordResetRequest
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(nullable: true)]
    private ?int $reset_code = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $expires_at = null;

    #[ORM\ManyToOne(inversedBy: 'passwordResetRequests')]
    private ?User $user = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getResetCode(): ?int
    {
        return $this->reset_code;
    }

    public function setResetCode(?int $reset_code): static
    {
        $this->reset_code = $reset_code;

        return $this;
    }

    public function getExpiresAt(): ?\DateTimeInterface
    {
        return $this->expires_at;
    }

    public function setExpiresAt(?\DateTimeInterface $expires_at): static
    {
        $this->expires_at = $expires_at;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
