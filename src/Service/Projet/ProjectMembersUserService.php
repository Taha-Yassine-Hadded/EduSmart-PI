<?php
namespace App\Service\Projet;

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
   // public function getNomByIdMember(int $idMember): ?string
    //{
    //    $userName = null;
     //   $query = "SELECT u.nom FROM App\Entity\User u JOIN u.projectMembers pm WHERE pm.id = :idMember";
      //  try {
      //      $statement = $this->entityManager->getConnection()->prepare($query);
        //    $statement->bindValue(':idMember', $idMember);
          /*  $statement->execute();
            $result = $statement->fetch();

            if ($result) {
                $userName = $result['nom'];
            }
        } catch (\Exception $e) {
            // Gérer les exceptions SQL de manière appropriée dans votre application
            echo "Erreur SQL : " . $e->getMessage();
        }
        return $userName;
    }*/

}
