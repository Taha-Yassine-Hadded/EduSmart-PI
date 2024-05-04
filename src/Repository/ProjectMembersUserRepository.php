<?php

namespace App\Repository;

use App\Entity\ProjectMembersUser;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectMembersUser>
 *
 * @method ProjectMembersUser|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectMembersUser|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectMembersUser[]    findAll()
 * @method ProjectMembersUser[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectMembersUserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectMembersUser::class);
    }

//    /**
//     * @return ProjectMembersUser[] Returns an array of ProjectMembersUser objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?ProjectMembersUser
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
