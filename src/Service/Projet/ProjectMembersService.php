<?php

namespace App\Service\Projet;

use App\Entity\ProjectMembers;
use Doctrine\ORM\EntityManagerInterface;

class ProjectMembersService
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    public function createProjectMember(ProjectMembers $projectMember)
    {
        $this->entityManager->persist($projectMember);
        $this->entityManager->flush();
    }

    public function updateProjectMember(ProjectMembers $projectMember)
    {
        // Pas besoin de faire de persist() car Doctrine gère les modifications automatiquement
        $this->entityManager->flush();
    }

    public function deleteProjectMember(ProjectMembers $projectMember)
    {
        $this->entityManager->remove($projectMember);
        $this->entityManager->flush();
    }

    public function findById($id): ?ProjectMembers
    {
        return $this->entityManager->getRepository(ProjectMembers::class)->find($id);
    }
    public function findMembersByProjectId($projectId)
    {
        try {
            return $this->entityManager->getRepository(ProjectMembers::class)->findBy(['project' => $projectId]);
        } catch (\Exception $e) {
            // Log de l'erreur
            $this->logger->error('Erreur lors de la récupération des membres du projet: ' . $e->getMessage());
            // Renvoyer une exception pour propagation
            throw $e;
        }
    }


    public function findIdMemberByUserIdAndProjectId($userId, $projectId)
    {
        try {
            $query = $this->entityManager->createQuery(
                'SELECT pm.id 
             FROM App\Entity\ProjectMembers pm
             JOIN pm.student s
             JOIN pm.project p
             WHERE s.id = :userId
             AND p.id = :projectId'
            );
            $query->setParameter('userId', $userId);
            $query->setParameter('projectId', $projectId);

            return $query->getSingleScalarResult();
        } catch (\Exception $e) {
            // Log de l'erreur
            $this->logger->error('Erreur lors de la récupération de l\'ID du membre: ' . $e->getMessage());
            // Renvoyer une exception pour propagation
            throw $e;
        }
    }



}
