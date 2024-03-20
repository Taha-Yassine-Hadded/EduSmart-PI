<?php

namespace App\Repository;

use App\Entity\EventReactions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventReactions>
 *
 * @method EventReactions|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventReactions|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventReactions[]    findAll()
 * @method EventReactions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventReactionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventReactions::class);
    }

//    /**
//     * @return EventReactions[] Returns an array of EventReactions objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?EventReactions
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
