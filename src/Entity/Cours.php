<?php

namespace App\Entity;

use App\Repository\CoursRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CoursRepository::class)]
class Cours
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Cours name cannot be empty")]
    #[Assert\Length(
        min: 3,
        max: 10,
        minMessage: "The course name must be at least {{ limit }} characters long",
        maxMessage: "The course name cannot be longer than {{ limit }} characters"
    )]
    private ?string $nom_cours = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message:"Module name cannot be empty")]
    #[Assert\Length(
        min: 3,
        max: 10,
        minMessage: "The module name must be at least {{ limit }} characters long",
        maxMessage: "The module name cannot be longer than {{ limit }} characters"
    )]
    private ?string $nom_module = null;

    #[ORM\Column(nullable: true)]
    #[Assert\NotBlank(message:"niveau  cannot be empty")]
    #[Assert\Regex(
        pattern: '/^\d+$/',
        message: "Course level must be an integer"
    )]
    private ?int $niveau = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $coursURLpdf = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    private ?User $teacher = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomCours(): ?string
    {
        return $this->nom_cours;
    }

    public function setNomCours(?string $nom_cours): static
    {
        $this->nom_cours = $nom_cours;

        return $this;
    }    
    public function getNomModule(): ?string
    {
        return $this->nom_module;
    }

    public function setNomModule(?string $nom_module): static
    {
        $this->nom_module = $nom_module;

        return $this;
    }

    public function getNiveau(): ?int
    {
        return $this->niveau;
    }

    public function setNiveau(?int $niveau): static
    {
        $this->niveau = $niveau;

        return $this;
    }

    public function getTeacher(): ?User
    {
        return $this->teacher;
    }

    public function setTeacher(?User $teacher): static
    {
        $this->teacher = $teacher;

        return $this;
    }
    public function getCoursURLpdf(): ?string
    {
        return $this->coursURLpdf;
    }

    public function setCoursURLpdf(?string $coursURLpdf): static
    {
        $this->coursURLpdf = $coursURLpdf;

        return $this;
    }
}
