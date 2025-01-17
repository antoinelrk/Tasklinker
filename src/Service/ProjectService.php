<?php

namespace App\Service;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProjectService
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {}

    public function delete(Project $project): bool
    {
        // TODO: Faire les vérifications
        $this->entityManager->remove($project);
        $this->entityManager->flush();

        return true;
    }

//    public function create(Project $project): bool|Project
//    {
//
//    }
//
//    public function update(Project $project)
//    {
//
//    }
}