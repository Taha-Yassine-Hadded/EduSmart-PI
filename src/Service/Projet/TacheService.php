<?php

namespace App\Service\Projet;

use App\Entity\Tache;
use App\Repository\TacheRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\EtatEnum;


class TacheService
{
    private $entityManager;
    private $tacheRepository;

    public function __construct(EntityManagerInterface $entityManager, TacheRepository $tacheRepository)
    {
        $this->entityManager = $entityManager;
        $this->tacheRepository = $tacheRepository;
    }

    public function ajouterTache(Tache $tache): void
    {
        $this->entityManager->persist($tache);
        $this->entityManager->flush();
    }


    public function modifierTache(Tache $tache)
    {
        $this->entityManager->flush();
    }

    public function deleteTaskById($taskId)
    {
        // Récupérer la tâche à supprimer par son ID
        $task = $this->entityManager->getRepository(Tache::class)->find($taskId);

        // Vérifier si la tâche existe
        if (!$task) {
            throw new \Exception('Tâche non trouvée');
        }

        try {
            // Supprimer la tâche
            $this->entityManager->remove($task);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            // Gérer toute erreur lors de la suppression de la tâche
            throw new \Exception('Erreur lors de la suppression de la tâche : ' . $e->getMessage());
        }
    }

    public function getAllTaches()
    {
        return $this->tacheRepository->findAll();
    }

    public function getTacheById($id)
    {
        return $this->tacheRepository->find($id);
    }

    public function getTachesByUserIdAndProjectId($userId, $projectId)
    {
        return $this->entityManager->createQuery(
            'SELECT t
        FROM App\Entity\Tache t
        JOIN t.member pm
        JOIN pm.student u
        JOIN pm.project p
        WHERE u.id = :userId AND p.id = :projectId'
        )
            ->setParameter('userId', $userId)
            ->setParameter('projectId', $projectId)
            ->getResult();
    }
    public function updateEtat($tacheId, $newState)
    {
        $tache = $this->entityManager->getRepository(Tache::class)->find($tacheId);

        if (!$tache) {
            throw new \Exception('Tâche non trouvée');
        }

        // Vérifier si l'état est valide
        if (!EtatEnum::isValid($newState)) {
            throw new \Exception('État de tâche invalide');
        }

        // Mettre à jour l'état de la tâche
        $tache->setEtat($newState);

        // Enregistrer les modifications dans la base de données
        $this->entityManager->flush();

        return $tache;
    }
    // Remplacez la méthode updateDescription par updateTaskDescription
    public function updateTaskDescription($taskId, $newDescription)
    {
        $tache = $this->entityManager->getRepository(Tache::class)->find($taskId);

        if (!$tache) {
            throw new \Exception('Tâche non trouvée');
        }

        $tache->setDescription($newDescription);

        $this->entityManager->flush();
    }


    public function updateDeadline($tacheId, $newDeadline)
    {
        $tache = $this->entityManager->getRepository(Tache::class)->find($tacheId);

        if (!$tache) {
            throw new \Exception('Tâche non trouvée');
        }

        // Convertir la nouvelle date de délai en objet DateTimeInterface
        $newDeadlineDate = \DateTime::createFromFormat('Y-m-d', $newDeadline);
        if (!$newDeadlineDate) {
            throw new \Exception('Format de date invalide pour la nouvelle date de délai');
        }

        $tache->setDedline($newDeadlineDate);

        // Enregistrer les modifications dans la base de données
        $this->entityManager->flush();

        return $tache;
    }

}
