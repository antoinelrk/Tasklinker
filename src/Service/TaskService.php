<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

readonly class TaskService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    public function delete(Task $task): bool
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return true;
    }
}

