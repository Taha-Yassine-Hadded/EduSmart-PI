<?php

namespace App\Repository;

use App\Entity\ValidationCode;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ValidationCode>
 *
 * @method ValidationCode|null find($id, $lockMode = null, $lockVersion = null)
 * @method ValidationCode|null findOneBy(array $criteria, array $orderBy = null)
 * @method ValidationCode[]    findAll()
 * @method ValidationCode[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ValidationCodeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ValidationCode::class);
    }

}
