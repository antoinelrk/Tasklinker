<?php

namespace App\Service;

use App\Entity\Project;
use Doctrine\ORM\EntityManagerInterface;

readonly class ProjectService
{
    public function __construct(
        protected EntityManagerInterface $entityManager
    ) {}

    /**
     * @param Project $project
     * @return bool
     */
    public function delete(Project $project): bool
    {
        $this->entityManager->remove($project);
        $this->entityManager->flush();

        return true;
    }

    /**
     * @param Project $project
     * @return bool
     */
    public function archive(Project $project): bool
    {
        try {
            $this->entityManager->beginTransaction();

            $project->setUpdatedAt(new \DateTimeImmutable());
            $project->setDeletedAt(new \DateTimeImmutable());

            $this->entityManager->persist($project);
            $this->entityManager->flush();

            $this->entityManager->commit();

            return true;
        } catch (\Exception $e) {
            $this->entityManager->rollback();

            return false;
        }
    }
}