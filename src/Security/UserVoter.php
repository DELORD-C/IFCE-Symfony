<?php

namespace App\Security;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    function supports (string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [
            self::VIEW,
            self::EDIT,
            self::DELETE
        ])) {
           return false;
        }

        if (!$subject instanceof User) {
            return false;
        }

        return true;
    }

    function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof User) {
            return false;
        }

        return match($attribute) {
            self::VIEW, self::DELETE => $this->canDelete($user),
            self::EDIT => $this->canEdit($subject, $user)
        };
    }

    function canEdit (User $user, User $loggedIn): bool
    {
        return $user === $loggedIn || $this->canDelete($loggedIn);
    }

    function canDelete (User $loggedIn): bool
    {
        return in_array('ROLE_ADMIN', $loggedIn->getRoles());
    }
}