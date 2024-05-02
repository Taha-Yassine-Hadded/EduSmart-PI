<?php

namespace App\Repository;

use App\Entity\FollowNotification;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<FollowNotification>
 *
 * @method FollowNotification|null find($id, $lockMode = null, $lockVersion = null)
 * @method FollowNotification|null findOneBy(array $criteria, array $orderBy = null)
 * @method FollowNotification[]    findAll()
 * @method FollowNotification[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FollowNotificationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FollowNotification::class);
    }

    public function findByUserOrderedByDateDesc($user)
    {
        return $this->createQueryBuilder('n')
            ->where('n.user = :user')
            ->setParameter('user', $user)
            ->orderBy('n.createdAt', 'DESC') // Order by createdAt in descending order
            ->getQuery()
            ->getResult();
    }

    public function countUnseenNotifications($user): int
    {
        return $this->createQueryBuilder('n')
            ->select('count(n.id)')
            ->where('n.user = :user')
            ->andWhere('n.seen = false')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

//    /**
//     * @return FollowNotification[] Returns an array of FollowNotification objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('f.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?FollowNotification
//    {
//        return $this->createQueryBuilder('f')
//            ->andWhere('f.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
