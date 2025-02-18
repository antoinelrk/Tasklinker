<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Project::class);
    }

    // ---------- HELPERS ----------

    public function first(): ?Project
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->where('p.deleted_at IS NULL')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function all(): ?array
    {
        return $this->createQueryBuilder('p')
            ->orderBy('p.id', 'ASC')
            ->where('p.deleted_at IS NULL')
            ->getQuery()
            ->getArrayResult();
    }
}
