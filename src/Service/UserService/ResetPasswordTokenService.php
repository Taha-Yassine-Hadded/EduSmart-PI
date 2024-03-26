<?php

namespace App\Service\UserService;

use App\Entity\ResetPasswordToken;
use App\Repository\ResetPasswordTokenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class ResetPasswordTokenService {
    
    private $repo;
    private $entityManager;

    public function __construct(ResetPasswordTokenRepository $repo, EntityManagerInterface $entityManager)
    {
        $this->repo = $repo;
        $this->entityManager = $entityManager;
    }
    
    public function add(ResetPasswordToken $resetPasswordToken) : ResetPasswordToken {
        try {
            $this->entityManager->persist($resetPasswordToken);
            $this->entityManager->flush();
    
            return $resetPasswordToken;
        } catch (\Exception $e) {
            throw new \Exception("There was an error adding the password reset token : " . $e->getMessage());
        }
    }

    public function getById(int $id) : ResetPasswordToken {
        return $this->repo->find($id);
    }

    public function delete(int $id): void {
        $req = $this->repo->find($id);
        if ($req === null) {
            throw new EntityNotFoundException('Token not found.');
        }
        $this->entityManager->remove($req);
        $this->entityManager->flush();
    }
}