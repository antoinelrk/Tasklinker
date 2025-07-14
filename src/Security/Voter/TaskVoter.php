<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class TaskVoter extends Voter {
    public const EDIT = 'TASK_EDIT';
    public const VIEW = 'TASK_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\Task;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($user->hasRole('ROLE_ADMIN')) {
            // Admins can do anything
            return true;
        }

        // ... (check conditions and return true to grant permission) ...
        return match ($attribute) {
            self::VIEW => $subject->getProject()->getUsers()->contains($user),
            default => false,
        };

    }
}
