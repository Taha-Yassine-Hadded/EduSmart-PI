<?php

namespace App\Service\Projet;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;
class ProjectService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function getProjectNamesForUserId(int $userId): array
    {
        $query = $this->entityManager->createQuery(
            "SELECT p.nom , p.id
        FROM App\Entity\Project p
        JOIN p.projectMembers pm
        JOIN pm.student pmu
        WHERE pmu.id = :userId"
        );
        $query->setParameter('userId', $userId);

        $projectNames = $query->getResult();

        foreach ($projectNames as $projectName) {
            echo $projectName['nom'] . "\n";
        }

        return $projectNames;
    }

    public function add(Project $project)
    {
        try {
            $this->entityManager->persist($project);
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception("Échec de l'ajout du projet : " . $e->getMessage());
        }
    }

    public function delete(int $id)
    {
        $project = $this->entityManager->getRepository(Project::class)->find($id);
        if (!$project) {
            throw new \Exception("Aucun projet trouvé pour l'ID de projet : " . $id);
        }
        $this->entityManager->remove($project);
        $this->entityManager->flush();
    }
    public function update(Project $project)
    {
        try {
            $this->entityManager->flush();
        } catch (\Exception $e) {
            throw new \Exception("Échec de la mise à jour du projet : " . $e->getMessage());
        }
    }


    public function getAll(): array
    {
        return $this->entityManager->getRepository(Project::class)->findAll();
    }

    public function getById(int $id): ?Project
    {
        return $this->entityManager->getRepository(Project::class)->find($id);
    }
    public function getAllProjectsForTeacher(int $teacherId): array
    {
        return $this->entityManager->getRepository(Project::class)->findBy(['teacher' => $teacherId]);
    }

}
