<?php

namespace App\Entity;

use App\Repository\ProjectMembersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectMembersRepository::class)]
class ProjectMembers
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'projectMembers')]
    private ?project $project = null;

    #[ORM\ManyToMany(targetEntity: user::class, inversedBy: 'projectMembers')]
    private Collection $student;

    #[ORM\Column]
    private ?bool $is_owner = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $joined_at = null;

    public function __construct()
    {
        $this->student = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getProject(): ?project
    {
        return $this->project;
    }

    public function setProject(?project $project): static
    {
        $this->project = $project;

        return $this;
    }

    /**
     * @return Collection<int, user>
     */
    public function getStudent(): Collection
    {
        return $this->student;
    }

    public function addStudent(user $student): static
    {
        if (!$this->student->contains($student)) {
            $this->student->add($student);
        }

        return $this;
    }

    public function removeStudent(user $student): static
    {
        $this->student->removeElement($student);

        return $this;
    }

    public function isIsOwner(): ?bool
    {
        return $this->is_owner;
    }

    public function setIsOwner(bool $is_owner): static
    {
        $this->is_owner = $is_owner;

        return $this;
    }

    public function getJoinedAt(): ?\DateTimeInterface
    {
        return $this->joined_at;
    }

    public function setJoinedAt(\DateTimeInterface $joined_at): static
    {
        $this->joined_at = $joined_at;

        return $this;
    }
}
