<?php

namespace App\Entity;

use App\Repository\ProjectRepository;
use DateTimeImmutable;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProjectRepository::class)]
class Project extends Entity
{
    /**
     * @var int|null
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $likely_end_at = null;

    /**
     * @var bool|null
     */
    #[ORM\Column]
    private ?bool $archived = false;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $started_at = null;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $ended_at = null;

    /**
     * @ORM\Column(type="datetime_immutable")
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, mappedBy: 'projects', fetch: 'EAGER')]
    private Collection $users;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'project', cascade: ['remove'], fetch: 'EAGER', orphanRemoval: true)]
    private Collection $tasks;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deleted_at = null;

    /**
     * Project constructor.
     */
    public function __construct()
    {
        $this->users = new ArrayCollection();
        $this->created_at = new \DateTimeImmutable('now');
        $this->tasks = new ArrayCollection();
    }

    /**
     * Get the ID of the project.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the ID of the project.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the name of the project.
     *
     * @param string $name
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the likely end date of the project.
     *
     * @return \DateTimeImmutable|null
     */
    public function getLikelyEndAt(): ?\DateTimeImmutable
    {
        return $this->likely_end_at;
    }

    /**
     * Set the likely end date of the project.
     *
     * @param \DateTimeImmutable|null $likely_end_at
     * @return static
     */
    public function setLikelyEndAt(?\DateTimeImmutable $likely_end_at): static
    {
        $this->likely_end_at = $likely_end_at;

        return $this;
    }

    /**
     * Check if the project is archived.
     *
     * @return bool|null
     */
    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    /**
     * Set the archived status of the project.
     *
     * @param bool $archived
     * @return static
     */
    public function setArchived(bool $archived): static
    {
        $this->archived = $archived;

        return $this;
    }

    /**
     * Get the start date of the project.
     *
     * @return \DateTimeImmutable|null
     */
    public function getStartedAt(): ?\DateTimeImmutable
    {
        return $this->started_at;
    }

    /**
     * Set the start date of the project.
     *
     * @param \DateTimeImmutable|null $started_at
     * @return static
     */
    public function setStartedAt(?\DateTimeImmutable $started_at): static
    {
        $this->started_at = $started_at;

        return $this;
    }

    /**
     * Get the end date of the project.
     *
     * @return \DateTimeImmutable|null
     */
    public function getEndedAt(): ?\DateTimeImmutable
    {
        return $this->ended_at;
    }

    /**
     * Set the end date of the project.
     *
     * @param \DateTimeImmutable|null $ended_at
     * @return static
     */
    public function setEndedAt(?\DateTimeImmutable $ended_at): static
    {
        $this->ended_at = $ended_at;

        return $this;
    }

    /**
     * Get the creation date of the project.
     *
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * Set the creation date of the project.
     *
     * @param \DateTimeImmutable $created_at
     * @return static
     */
    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the last updated date of the project.
     *
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * Set the last updated date of the project.
     *
     * @param \DateTimeImmutable|null $updated_at
     * @return static
     */
    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the users associated with the project.
     *
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    /**
     * Add a user to the project.
     *
     * @param User $user
     * @return static
     */
    public function addUser(User $user): static
    {
        if (!$this->users->contains($user)) {
            $this->users->add($user);
            $user->addProject($this);
        }

        return $this;
    }

    /**
     * Remove a user from the project.
     *
     * @param User $user
     * @return static
     */
    public function removeUser(User $user): static
    {
        if ($this->users->removeElement($user)) {
            $user->removeProject($this);
        }

        return $this;
    }

    /**
     * Get the tasks associated with the project.
     *
     * @return Collection<int, Task>
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * Add a task to the project.
     *
     * @param Task $task
     * @return static
     */
    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setProject($this);
        }

        return $this;
    }

    /**
     * Remove a task from the project.
     *
     * @param Task $task
     * @return static
     */
    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            // set the owning side to null (unless already changed)
            if ($task->getProject() === $this) {
                $task->setProject(null);
            }
        }

        return $this;
    }

    /**
     * Get deletion date of the project.
     *
     * @return DateTimeImmutable|null
     */
    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deleted_at;
    }

    /**
     * Set the deletion date of the project.
     *
     * @param \DateTimeImmutable|null $deleted_at
     * @return static
     */
    public function setDeletedAt(?\DateTimeImmutable $deleted_at): static
    {
        $this->deleted_at = $deleted_at;

        return $this;
    }
}
