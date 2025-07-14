<?php

namespace App\Entity;

use App\Repository\TaskRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TaskRepository::class)]
class Task
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
    #[ORM\Column(length: 128)]
    private ?string $title = null;

    /**
     * @var string|null
     */
    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $body = null;

    /**
     * @var string|null
     */
    #[ORM\Column(length: 255)]
    private ?string $state = null;

    /**
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $likely_end_at = null;

    /**
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var \DateTimeImmutable|null
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var Project|null
     */
    #[ORM\ManyToOne(targetEntity: Project::class, inversedBy: 'tasks')]
    #[ORM\JoinColumn(nullable: false, onDelete: 'CASCADE')]
    private ?Project $project = null;

    /**
     * @var User|null
     */
    #[ORM\ManyToOne(inversedBy: 'tasks')]
    private ?User $User = null;

    /**
     * Get the ID of the task.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Get the title of the task.
     *
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * Set the title of the task.
     *
     * @param string $title
     * @return static
     */
    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get the body of the task.
     *
     * @return string|null
     */
    public function getBody(): ?string
    {
        return $this->body;
    }

    /**
     * Set the body of the task.
     *
     * @param string|null $body
     * @return static
     */
    public function setBody(?string $body): static
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Get the state of the task.
     *
     * @return string|null
     */
    public function getState(): ?string
    {
        return $this->state;
    }

    /**
     * Set the state of the task.
     *
     * @param string $state
     * @return static
     */
    public function setState(string $state): static
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get the likely end date of the task.
     *
     * @return \DateTimeImmutable|null
     */
    public function getLikelyEndAt(): ?\DateTimeImmutable
    {
        return $this->likely_end_at;
    }

    /**
     * Set the likely end date of the task.
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
     * Get the creation date of the task.
     *
     * @return \DateTimeImmutable|null
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * Set the creation date of the task.
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
     * Get the last updated date of the task.
     *
     * @return \DateTimeImmutable|null
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * Set the last updated date of the task.
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
     * Get the project associated with the task.
     *
     * @return Project|null
     */
    public function getProject(): ?Project
    {
        return $this->project;
    }

    /**
     * Set the project associated with the task.
     *
     * @param Project|null $project
     * @return static
     */
    public function setProject(?Project $project): static
    {
        $this->project = $project;

        return $this;
    }

    /**
     * Remove the project associated with the task.
     *
     * @return User|null
     */
    public function getUser(): ?User
    {
        return $this->User;
    }

    /**
     * Set the user associated with the task.
     *
     * @param User|null $User
     * @return static
     */
    public function setUser(?User $User): static
    {
        $this->User = $User;

        return $this;
    }

    /**
     * Remove the user associated with the task.
     *
     * @return static
     */
    public function removeUser(): static
    {
        $this->User = null;

        return $this;
    }

    /**
     * Get the initials of the user associated with the task.
     *
     * @return string
     */
    public function getUserInitials(): string
    {
        return $this->User->getInitials();
    }
}
