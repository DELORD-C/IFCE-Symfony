<?php

namespace App\Security;

use App\Entity\Post;
use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class PostVoter extends Voter
{
    const CREATE = 'create';
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';

    function supports (string $attribute, mixed $subject): bool
    {
        if (!in_array($attribute, [
            self::CREATE,
            self::VIEW,
            self::EDIT,
            self::DELETE
        ])) {
           return false;
        }

        if (!$subject instanceof Post) {
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
            self::CREATE, self::VIEW => true,
            self::EDIT, self::DELETE => $this->canEditOrDelete($subject, $user)
        };
    }

    function canEditOrDelete (Post $post, User $user): bool
    {
        return $user === $post->getUser() || in_array('ROLE_ADMIN', $user->getRoles());
    }
}