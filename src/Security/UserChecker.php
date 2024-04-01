<?php

namespace App\Security;

use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use App\Entity\User;

class UserChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user): void
    {
        if (!$user instanceof User) {
            return;
        }

        // Check if the user's 'enabled' attribute is set to false
        if (!$user->getIsEnabled()) {
            throw new CustomUserMessageAccountStatusException('Votre compte est désactivé.');
        }
    }

    public function checkPostAuth(UserInterface $user): void
    {
        // You can also perform checks after authentication here
    }
}
