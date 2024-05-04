<?php

namespace App\Service\Projet;

use App\Entity\Fichier;
use Doctrine\ORM\EntityManagerInterface;

class FichierService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveFichier(Fichier $fichier): void
    {
        $this->entityManager->persist($fichier);
        $this->entityManager->flush();
    }

    public function deleteFichier(Fichier $fichier): void
    {
        $this->entityManager->remove($fichier);
        $this->entityManager->flush();
    }

    public function getFichierById(int $id): ?Fichier
    {
        return $this->entityManager->getRepository(Fichier::class)->find($id);
    }
    public function getFichiersByProjectId(int $projectId): array
    {
        $fichiers = [];
        $sql = "SELECT f FROM App\Entity\Fichier f JOIN f.member pm WHERE pm.project = :projectId";
        try {
            $query = $this->entityManager->createQuery($sql);
            $query->setParameter('projectId', $projectId);
            $fichiers = $query->getResult();
        } catch (\Exception $e) {
            // Gérer l'erreur
            echo "Erreur lors de la récupération des fichiers : " . $e->getMessage();
        }
        return $fichiers;
    }
}
