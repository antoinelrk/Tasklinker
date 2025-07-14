<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\SecurityBundle\Security;

/**
 * @extends ServiceEntityRepository<Project>
 */
class ProjectRepository extends ServiceEntityRepository
{
    protected Security $security;

    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, Project::class);

        $this->security = $security;
    }

    // ---------- HELPERS ----------

    public function current(): ?array
    {
        $projects = $this->security->getUser()?->getProjects();

        if (!$projects) {
            return null;
        }

        return $projects->toArray();
    }

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
