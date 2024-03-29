<?php

namespace App\Repository;

use App\Entity\ResetPasswordToken;
use App\Entity\RoleEnum;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<User>
 *
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * @param RoleEnum $role
     * @return User[]
     */
    public function getByRole(RoleEnum $role): array
    {   
        $qb = $this->createQueryBuilder('u');

        $query = $qb->where('u.role = :role')
                    ->setParameter('role', $role->value)
                    ->getQuery();

        return $query->getResult();
    }


    /**
     * Find a user by their email.
     *
     * @param string $email The user's email address.
     * @return User|null The user if found, null otherwise.
     */
    public function getByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }


    public function changeRoleFromStudentToClub(int $id)
    {
    $qb = $this->getEntityManager()->createQueryBuilder();
    $qb->update(User::class, 'u')
       ->set('u.role', ':newRole')
       ->where('u.id = :id')
       ->andWhere('u.role = :oldRole')
       ->setParameter('newRole', RoleEnum::CLUB->value)
       ->setParameter('oldRole', RoleEnum::STUDENT->value)
       ->setParameter('id', $id)
       ->getQuery()
       ->execute();
    }


    public function changeRoleFromClubToStudent(int $id)
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $qb->update(User::class, 'u')
       ->set('u.role', ':newRole')
       ->where('u.id = :id')
       ->andWhere('u.role = :oldRole')
       ->setParameter('newRole', RoleEnum::STUDENT->value)
       ->setParameter('oldRole', RoleEnum::CLUB->value)
       ->setParameter('id', $id)
       ->getQuery()
       ->execute();
    }

    public function getCountByRole(): array {
        $roleCounts = [];
        foreach (RoleEnum::cases() as $roleCase) {
            $roleCounts[$roleCase->value] = 0;
        }

        $qb = $this->createQueryBuilder('u')
            ->select('u.role AS role, COUNT(u.id) AS count')
            ->groupBy('u.role');

        $results = $qb->getQuery()->getResult();

        foreach ($results as $result) {
            $roleStr = $result['role'];
            $count = (int) $result['count'];

            $roleEnum = RoleEnum::tryFrom($roleStr->value);
            if ($roleEnum !== null) {
                $roleCounts[$roleEnum->value] = $count;
            }
        }

        return $roleCounts;
    }

    public function findByResetPasswordToken(string $tokenValue)
    {
        return $this->createQueryBuilder('u')
            ->innerJoin(ResetPasswordToken::class, 'rpt', 'WITH', 'u.id = rpt.user')
            ->where('rpt.token = :token')
            ->setParameter('token', $tokenValue)
            ->getQuery()
            ->getOneOrNullResult();
    }
    

}
