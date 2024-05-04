<?php

namespace App\Service\Projet;

use App\Entity\Matiere;
use Doctrine\ORM\EntityManagerInterface;

class MatiereService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getMatieres(): array
    {
        // Récupérer toutes les matières depuis la base de données
        return $this->entityManager->getRepository(Matiere::class)->findAll();
    }

    public function matiereExists(string $matiere): bool
    {
        // Vérifier si une matière avec le nom spécifié existe dans la base de données
        $matiereObject = $this->entityManager->getRepository(Matiere::class)->findOneBy(['matiere' => $matiere]);
        return $matiereObject !== null;
    }
}
