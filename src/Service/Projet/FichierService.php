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



    // Ajoutez d'autres méthodes de service ici selon vos besoins
}
