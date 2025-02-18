<?php

namespace App\Service;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;

readonly class TaskService
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {}

    /**
     * @param Task $task
     * @return bool
     */
    public function delete(Task $task): bool
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return true;
    }
}

