<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class User extends Entity implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @var int|null The unique identifier of the user
     */
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @var string|null The name of the user
     */
    #[ORM\Column(length: 48)]
    private ?string $name = null;

    /**
     * @var string|null The first name of the user
     */
    #[ORM\Column(length: 24)]
    private ?string $first_name = null;

    /**
     * @var string|null The email address of the user
     */
    #[ORM\Column(length: 128)]
    private ?string $email = null;

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    /**
     * @var bool|null Indicates if the user is enabled
     */
    #[ORM\Column]
    private ?bool $enabled = null;

    /**
     * @var \DateTimeImmutable|null The date when the user started employment
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $employment_started_at = null;

    /**
     * @var \DateTimeImmutable The date when the user was created
     */
    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    /**
     * @var \DateTimeImmutable|null The date when the user was last updated
     */
    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    /**
     * @var string|null The employment contract type
     *
     * @see EmploymentContractEnum
     */
    #[ORM\Column(length: 64)]
    private ?string $employment_contract = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var Collection<int, Project>
     */
    #[ORM\ManyToMany(targetEntity: Project::class, inversedBy: 'users', cascade: ['persist'])]
    private Collection $projects;

    /**
     * @var Collection<int, Task>
     */
    #[ORM\OneToMany(targetEntity: Task::class, mappedBy: 'User')]
    #[ORM\JoinColumn(nullable: true, onDelete: 'SET NULL')]
    private Collection $tasks;

    /**
     * User constructor.
     * Initializes the collections for projects and tasks.
     */
    public function __construct()
    {
        $this->projects = new ArrayCollection();
        $this->tasks = new ArrayCollection();
    }

    /**
     * Get the unique identifier of the user.
     *
     * @return int|null The unique identifier
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Set the unique identifier of the user.
     *
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Set the name of the user.
     *
     * @param string $name The name to set
     * @return static
     */
    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the first name of the user.
     *
     * @return string|null The first name
     */
    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    /**
     * Set the first name of the user.
     *
     * @param string $first_name The first name to set
     * @return static
     */
    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    /**
     * Get the email address of the user.
     *
     * @return string|null The email address
     */
    public function getEmail(): ?string
    {
        return $this->email;
    }

    /**
     * Set the email address of the user.
     *
     * @param string $email The email address to set
     * @return static
     */
    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Check if the user is enabled.
     *
     * @return bool|null True if the user is enabled, false otherwise
     */
    public function isEnabled(): ?bool
    {
        return $this->enabled;
    }

    /**
     * Set the enabled status of the user.
     *
     * @param bool $enabled True to enable the user, false to disable
     * @return static
     */
    public function setEnabled(bool $enabled): static
    {
        $this->enabled = $enabled;

        return $this;
    }

    /**
     * Get the date when the user started employment.
     *
     * @return \DateTimeImmutable|null The employment start date
     */
    public function getEmploymentStartedAt(): ?\DateTimeImmutable
    {
        return $this->employment_started_at;
    }

    /**
     * Set the date when the user started employment.
     *
     * @param \DateTimeImmutable|null $employment_started_at The employment start date to set
     * @return static
     */
    public function setEmploymentStartedAt(?\DateTimeImmutable $employment_started_at): static
    {
        $this->employment_started_at = $employment_started_at;

        return $this;
    }

    /**
     * Get the date when the user was created.
     *
     * @return \DateTimeImmutable|null The creation date
     */
    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    /**
     * Set the date when the user was created.
     *
     * @param \DateTimeImmutable $created_at The creation date to set
     * @return static
     */
    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    /**
     * Get the date when the user was last updated.
     *
     * @return \DateTimeImmutable|null The last update date
     */
    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    /**
     * Set the date when the user was last updated.
     *
     * @param \DateTimeImmutable|null $updated_at The last update date to set
     * @return static
     */
    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    /**
     * Get the employment contract type.
     *
     * @return string|null The employment contract type
     */
    public function getEmploymentContract(): ?string
    {
        return $this->employment_contract;
    }

    /**
     * Set the employment contract type.
     *
     * @param string $employment_contract The employment contract type to set
     * @return static
     */
    public function setEmploymentContract(string $employment_contract): static
    {
        $this->employment_contract = $employment_contract;

        return $this;
    }

    /**
     * Get the user's projects.
     *
     * @return Collection<int, Project> The collection of projects associated with the user
     */
    public function getProjects(): Collection
    {
        return $this->projects;
    }

    /**
     * Add a project to the user's projects.
     *
     * @param Project $project The project to add
     * @return static
     */
    public function addProject(Project $project): static
    {
        if (!$this->projects->contains($project)) {
            $this->projects->add($project);
        }

        return $this;
    }

    /**
     * Remove a project from the user's projects.
     *
     * @param Project $project The project to remove
     * @return static
     */
    public function removeProject(Project $project): static
    {
        $this->projects->removeElement($project);

        return $this;
    }

    /**
     * Get the tasks associated with the user.
     *
     * @return Collection<int, Task> The collection of tasks
     */
    public function getTasks(): Collection
    {
        return $this->tasks;
    }

    /**
     * Add a task to the user's tasks.
     *
     * @param Task $task The task to add
     * @return static
     */
    public function addTask(Task $task): static
    {
        if (!$this->tasks->contains($task)) {
            $this->tasks->add($task);
            $task->setUser($this);
        }

        return $this;
    }

    /**
     * Remove tasks from the user's tasks.
     *
     * @param Task $task
     * @return $this
     */
    public function removeTask(Task $task): static
    {
        if ($this->tasks->removeElement($task)) {
            if ($task->getUser() === $this) {
                $task->setUser(null);
            }
        }

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * Returns the roles granted to the user.
     *
     * @return array<string> The user roles
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * Sets the roles for the user.
     *
     * @param array<string> $roles The user roles
     * @return static
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * Returns the password used to authenticate the user.
     *
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * Sets the password for the user.
     *
     * @param string $password The hashed password
     * @return static
     */
    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    // ---------- HELPERS ----------

    /**
     * Get the initials of the user.
     *
     * @return string The initials in uppercase, e.g., "JD" for "John Doe"
     */
    public function getInitials(): string
    {
        return strtoupper($this->getFirstName()[0] . $this->getName()[0]);
    }

    /**
     * Get the full name of the user.
     *
     * @return string The full name in the format "FirstName LastName"
     */
    public function getFullName(): string
    {
        return $this->getFirstName() . ' ' . $this->getName();
    }

    /**
     * Check if the user is an admin.
     *
     * @return bool True if the user has the ROLE_ADMIN, false otherwise
     */
    public function isAdmin(): bool
    {
        return in_array('ROLE_ADMIN', $this->roles);
    }

    /**
     * Check if the user has a specific role.
     *
     * @param string $role The role to check
     * @return bool True if the user has the role, false otherwise
     */
    public function hasRole(string $role): bool
    {
        return in_array($role, $this->roles);
    }

    /**
     * Erases any sensitive data stored on the user.
     *
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // TODO: Implement eraseCredentials() method.
    }
}
