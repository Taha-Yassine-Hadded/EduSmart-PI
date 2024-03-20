<?php

namespace App\Repository;

use App\Entity\ProjectMembers;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ProjectMembers>
 *
 * @method ProjectMembers|null find($id, $lockMode = null, $lockVersion = null)
 * @method ProjectMembers|null findOneBy(array $criteria, array $orderBy = null)
 * @method ProjectMembers[]    findAll()
 * @method ProjectMembers[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectMembersRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ProjectMembers::class);
    }

//    /**
//     * @return ProjectMembers[] Returns an array of ProjectMembers objects
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

//    public function findOneBySomeField($value): ?ProjectMembers
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
