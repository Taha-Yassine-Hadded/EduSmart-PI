<?php
namespace App\Service\UserService;
use App\Entity\RoleEnum;
use App\Entity\User;

interface UserServiceInterface {

    public function addAdmin(User $admin) : User;

    public function addTeacher(User $teacher) : User;

    public function addStudent(User $student) : User;

    public function addClub(User $student) : User;

    public function addEntreprise(User $entreprise) : User;

    public function getUserById(int $id) : ?User;

    public function getUserByEmail(string $email) : ?User;

    public function delete(int $id): void;

    public function getAll(): array;

    public function getUsersByRole(RoleEnum $role);

    public function blockUser(int $id) : void;

    public function unblockUser(int $id) : void;

    public function toClubRH(int $id) : void;

    public function toStudent(int $id) : void;

    public function changePassword(string $password, User $user) : void;

    public function getUserCountByRole(): array;

    //updates
}