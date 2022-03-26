<?php

namespace App\Security;

use App\Entity\User;
use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAccountStatusException;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserChecker implements UserCheckerInterface
{
    /**
     * @param UserInterface $user
     * @return bool
     */
    public function checkPreAuth(UserInterface $user): bool
    {
        $valid = true;

        if (!$user instanceof AppUser) {
            $valid = false;
        }

        /** @var User $user */

        if (!$user->getIsValid()) {
            $valid = false;
            throw new CustomUserMessageAccountStatusException("Ce compte n'est pas valide");
        }

        return $valid;
    }

    public function checkPostAuth(UserInterface $user): void
    {

    }
}