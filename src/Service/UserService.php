<?php

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class UserService
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {}

    /**
     * @param User $user
     * @return bool
     */
    public function delete(User $user): bool
    {
        $this->entityManager->remove($user);
        $this->entityManager->flush();

        return false;
    }
}