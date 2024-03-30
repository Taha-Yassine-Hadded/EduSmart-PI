<?php

namespace App\Service\UserService;
use App\Entity\RoleEnum;
use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityNotFoundException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserService implements UserServiceInterface {
    
    private $userRepository;
    private $entityManager;
    private $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher,UserRepository $userRepository, EntityManagerInterface $entityManager)
    {
        $this->userRepository = $userRepository;
        $this->entityManager = $entityManager;
        $this->passwordHasher = $passwordHasher;
    }
    
    public function addAdmin(User $admin) : User {
        try {
            $admin->setPassword($this->passwordHasher->hashPassword($admin, $admin->getPassword()));
            $admin->setRole(RoleEnum::ADMIN);
            $admin->setIsEnabled(true);
            $this->entityManager->persist($admin);
            $this->entityManager->flush();
    
            return $admin;
        } catch (\Exception $e) {
            throw new \Exception("There was an error adding the admin: " . $e->getMessage());
        }
    }

    public function addTeacher(User $teacher) : User {
        try {
            $teacher->setPassword($this->passwordHasher->hashPassword($teacher, $teacher->getPassword()));
            $teacher->setRole(RoleEnum::TEACHER);
            $teacher->setIsEnabled(true);
            $this->entityManager->persist($teacher);
            $this->entityManager->flush();
    
            return $teacher;
        } catch (\Exception $e) {
            throw new \Exception("There was an error adding the teacher: " . $e->getMessage());
        }
    }

    public function addStudent(User $student) : User {
        try {
            $student->setPassword($this->passwordHasher->hashPassword($student, $student->getPassword()));
            $student->setRole(RoleEnum::STUDENT);
            $student->setIsEnabled(true);
            $this->entityManager->persist($student);
            $this->entityManager->flush();
    
            return $student;
        } catch (\Exception $e) {
            throw new \Exception("There was an error adding the student: " . $e->getMessage());
        }
    }

    public function addEntreprise(User $entreprise) : User {
        try {
            $entreprise->setPassword($this->passwordHasher->hashPassword($entreprise, $entreprise->getPassword()));
            $entreprise->setRole(RoleEnum::ENTREPRISE);
            $entreprise->setIsEnabled(true);
            $this->entityManager->persist($entreprise);
            $this->entityManager->flush();
    
            return $entreprise;
        } catch (\Exception $e) {
            throw new \Exception("There was an error adding the entreprise: " . $e->getMessage());
        }
    }

    public function getUserById(int $id) : ?User {
        return $this->userRepository->find($id);
    }
    
    public function getUserByEmail(string $email) : ?User {
        return $this->userRepository->getByEmail($email);
    }

    public function delete(int $id): void {
        $user = $this->userRepository->find($id);
        if ($user === null) {
            throw new EntityNotFoundException('User not found.');
        }
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }

    public function getAll(): array {
        return $this->userRepository->findAll();
    }

    public function getUsersByRole(RoleEnum $role) : array
    {
        return $this->userRepository->getByRole($role);
    }

    public function blockUser(int $id) : void
    {
        $this->userRepository->find($id)->setIsEnabled(false);
    }

    public function unblockUser(int $id) : void
    {
        $this->userRepository->find($id)->setIsEnabled(true);
    }

    public function toClubRH(int $id) : void 
    {
        $this->userRepository->changeRoleFromStudentToClub($id);
    }

    public function toStudent(int $id) : void 
    {
        $this->userRepository->changeRoleFromClubToStudent($id);
    }

    public function changePassword(string $password, User $user) : void
    {
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $this->entityManager->persist($user);
        $this->entityManager->flush();
    }

    public function getUserCountByRole(): array 
    {
        return $this->userRepository->getCountByRole();
    }

    public function getUserByResetPassworToken(string $tokenValue): ?User
    {
        return $this->userRepository->findByResetPasswordToken($tokenValue);
    }

}