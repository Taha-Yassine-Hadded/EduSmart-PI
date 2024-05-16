<?php

namespace App\Repository;

use App\Entity\RememberToken;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RememberToken>
 *
 * @method RememberToken|null find($id, $lockMode = null, $lockVersion = null)
 * @method RememberToken|null findOneBy(array $criteria, array $orderBy = null)
 * @method RememberToken[]    findAll()
 * @method RememberToken[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RememberTokenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RememberToken::class);
    }

//    /**
//     * @return RememberToken[] Returns an array of RememberToken objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RememberToken
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
