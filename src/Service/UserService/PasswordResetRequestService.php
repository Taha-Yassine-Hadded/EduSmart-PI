<?php

namespace App\Service\UserService;

use App\Entity\PasswordResetRequest;
use App\Entity\User;
use App\Repository\PasswordResetRequestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class PasswordResetRequestService {
    
    private $repo;
    private $entityManager;

    public function __construct(PasswordResetRequestRepository $repo, EntityManagerInterface $entityManager)
    {
        $this->repo = $repo;
        $this->entityManager = $entityManager;
    }
    
    public function add(PasswordResetRequest $passwordResetRequest) : PasswordResetRequest {
        try {
            $this->entityManager->persist($passwordResetRequest);
            $this->entityManager->flush();
    
            return $passwordResetRequest;
        } catch (\Exception $e) {
            throw new \Exception("There was an error adding the password reset request: " . $e->getMessage());
        }
    }

    public function getById(int $id) : PasswordResetRequest {
        return $this->repo->find($id);
    }
    
    public function getByUser(User $user) : array {
        return $this->repo->findByUser($user);
    }

    public function delete(int $id): void {
        $req = $this->repo->find($id);
        if ($req === null) {
            throw new EntityNotFoundException('Request not found.');
        }
        $this->entityManager->remove($req);
        $this->entityManager->flush();
    }
}