<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User extends Entity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 48)]
    private ?string $name = null;

    #[ORM\Column(length: 24)]
    private ?string $first_name = null;

    #[ORM\Column(length: 128)]
    private ?string $email = null;

    #[ORM\ManyToOne(inversedBy: 'role')]
    private ?Role $role = null;

    #[ORM\Column]
    private ?bool $enabled = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $employement_started_at = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 64)]
    private ?string $employement_contract = null;

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: 'users', cascade: ['persist'])]
    private Collection $project;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'User')]
    private Collection $tasks;

    public function __construct()
    {
        $this->project = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getRole(): ?Role
    {
        return $this->role;
    }

    public function setRole(?Role $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getEmployementStartedAt(): ?\DateTimeImmutable
    {
        return $this->employement_started_at;
    }

    public function setEmployementStartedAt(?\DateTimeImmutable $employement_started_at): static
    {
        $this->employement_started_at = $employement_started_at;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getEmployementContract(): ?string
    {
        return $this->employement_contract;
    }

    public function setEmployementContract(string $employement_contract): static
    {
        $this->employement_contract = $employement_contract;

        return $this;
    }

    // ---------- HELPERS ----------
    public function getInitials(): string
    {
        return strtoupper($this->getFirstName()[0] . $this->getName()[0]);
    }

    /**
     * @return Collection<int, Project>
     */
    public function getProject(): Collection
    {
        return $this->project;
    }

    public function addProject(Project $project): static
    {
        if (!$this->project->contains($project)) {
            $this->project->add($project);
        }

        return $this;
    }

    public function removeProject(Project $project): static
    {
        $this->project->removeElement($project);

        return $this;
    }

    /**
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setUser($this);
        }

        return $this;
    }

    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
    }

    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getName();
    }
}
