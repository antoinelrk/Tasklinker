<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class ProjectVoter extends Voter {
    public const VIEW = 'PROJECT_VIEW';
    public const DELETE = 'PROJECT_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        return in_array($attribute, [self::VIEW, self::DELETE])
            && $subject instanceof \App\Entity\Project;
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
            self::VIEW => $user->getProjects()->contains($subject),
            self::DELETE => $user->hasRole('ROLE_ADMIN'),
            default => false,
        };

    }
}
