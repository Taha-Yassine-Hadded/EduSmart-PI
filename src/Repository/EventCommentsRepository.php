<?php

namespace App\Repository;

use App\Entity\EventComments;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<EventComments>
 *
 * @method EventComments|null find($id, $lockMode = null, $lockVersion = null)
 * @method EventComments|null findOneBy(array $criteria, array $orderBy = null)
 * @method EventComments[]    findAll()
 * @method EventComments[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class EventCommentsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, EventComments::class);
    }

//    /**
//     * @return EventComments[] Returns an array of EventComments objects
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

//    public function findOneBySomeField($value): ?EventComments
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
