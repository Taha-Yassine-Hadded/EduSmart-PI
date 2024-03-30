<?php

namespace App\Service\UserService;

use App\Entity\ValidationCode;
use App\Repository\ValidationCodeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;

class ValidationCodeService {
    
    private $repo;
    private $entityManager;

    public function __construct(ValidationCodeRepository $repo, EntityManagerInterface $entityManager)
    {
        $this->repo = $repo;
        $this->entityManager = $entityManager;
    }
    
    public function add(ValidationCode $validationCode) : ValidationCode {
        try {
            $this->entityManager->persist($validationCode);
            $this->entityManager->flush();
    
            return $validationCode;
        } catch (\Exception $e) {
            throw new \Exception("There was an error adding the password reset request: " . $e->getMessage());
        }
    }

    public function getById(int $id) : ValidationCode {
        return $this->repo->find($id);
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