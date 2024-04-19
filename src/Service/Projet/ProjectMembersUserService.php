<?php
namespace App\Service;

use App\Entity\ProjectMembersUser;
use Doctrine\ORM\EntityManagerInterface;

class ProjectMembersUserService
{
private $entityManager;

public function __construct(EntityManagerInterface $entityManager)
{
$this->entityManager = $entityManager;
}

public function ajouterMemberUser(ProjectMembersUser $projectMemberUser)
{
$this->entityManager->persist($projectMemberUser);
$this->entityManager->flush();
}
}
