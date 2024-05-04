<?php

namespace App\Entity;

use App\Repository\TacheRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TacheRepository::class)]
class Tache
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'taches')]
    #[ORM\JoinColumn(name: 'idMember', referencedColumnName: 'id')]
    private ?ProjectMembers $member = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?EtatEnum $etat = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $date_ajout = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $dedline = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMember(): ?ProjectMembers
    {
        return $this->member;
    }

    public function setMember(?ProjectMembers $member): static
    {
        $this->member = $member;

        return $this;
    }

    public function getEtat(): ?EtatEnum
    {
        return $this->etat;
    }

    public function setEtat(?EtatEnum $etat): static
    {
        $this->etat = $etat;

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

    public function getDateAjout(): ?\DateTimeInterface
    {
        return $this->date_ajout;
    }

    public function setDateAjout(?\DateTimeInterface $date_ajout): static
    {
        $this->date_ajout = $date_ajout;

        return $this;
    }

    public function getDedline(): ?\DateTimeInterface
    {
        return $this->dedline;
    }

    public function setDedline(?\DateTimeInterface $dedline): static
    {
        $this->dedline = $dedline;

        return $this;
    }
    public function dedlineToString(): ?string
    {
        // Vérifie si la date de délai est définie
        if ($this->dedline instanceof \DateTimeInterface) {
            // Formate la date selon le format souhaité (par exemple : 'Y-m-d H:i:s')
            return $this->dedline->format('Y-m-d H:i:s');
        }

        return null; // Retourne null si la date de délai n'est pas définie
    }
    public function updateDeadline($tacheId, $newDeadline)
    {
        $tache = $this->entityManager->getRepository(Tache::class)->find($tacheId);

        if (!$tache) {
            throw new \Exception('Tâche non trouvée');
        }

        $newDeadlineDate = new \DateTime($newDeadline);
        $tache->setDeadline($newDeadlineDate);

        // Enregistrer les modifications dans la base de données
        $this->entityManager->flush();

        return $tache;
    }

}
