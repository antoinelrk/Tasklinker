<?php

namespace App\DataFixtures;

use App\Entity\Project;
use App\Entity\Task;
use App\Entity\User;
use App\Enum\EmploymentContractEnum;
use App\Enum\TaskStateEnum;
use App\Repository\ProjectRepository;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function __construct(
        protected TaskRepository $taskRepository,
        protected ProjectRepository $projectRepository,
        protected UserRepository $userRepository,
    ) {}

    private function users(ObjectManager $manager): void
    {
        $users = [
            [
                'name' => 'Dillon',
                'first_name' => 'Natalie',
                'email' => 'natalie@driblet.com',
                'contract' => EmploymentContractEnum::CDI->value,
                'started_at' => '15604632',
            ],
            [
                'name' => 'Baker',
                'first_name' => 'Demi',
                'email' => 'demi@driblet.com',
                'contract' => EmploymentContractEnum::CDD->value,
                'started_at' => '15604632',
            ],
            [
                'name' => 'Dupont',
                'first_name' => 'Marie',
                'email' => 'marie@driblet.com',
                'contract' => EmploymentContractEnum::FREELANCE->value,
                'started_at' => '15604632',
            ]
        ];

        foreach ($users as $u) {
            $user = new User();
            $user->setName($u['name']);
            $user->setFirstName($u['first_name']);
            $user->setEmail($u['email']);
            $user->setEnabled(true);
            $user->setEmployementContract($u['contract']);
            $user->setEmployementStartedAt(new \DateTimeImmutable($u['started_at']));
            $user->setCreatedAt(new \DateTimeImmutable('now'));

            $this->flush($manager, $user);
        }
    }

    private function tasks(ObjectManager $manager): void
    {
        $task = new Task();
        $task->setTitle( 'Gestion des droits d\'accès');
        $task->setBody('Un employé ne peut accéder qu\'à ses projets');
        $task->setState(TaskStateEnum::TODO->value);
        $task->setProject($this->projectRepository->first());
        $task->setCreatedAt(new \DateTimeImmutable());

        $this->flush($manager, $task);
    }

    private function projects(ObjectManager $manager): void
    {
        $project = new Project();
        $project->setName('Site vitrine Les Soeurs Marchand');
        $project->setArchived(false);
        $project->setCreatedAt(new \DateTimeImmutable());
        $project->addUser($this->userRepository->first());

        $this->flush($manager, $project);

        $project = new Project();
        $project->setName('TaskLinker');
        $project->setArchived(false);
        $project->setCreatedAt(new \DateTimeImmutable());

        $this->flush($manager, $project);
    }

    public function load(ObjectManager $manager): void
    {
        $this->users($manager);
        $this->projects($manager);
        $this->tasks($manager);
    }

    private function flush($manager, $entity): void
    {
        $manager->persist($entity);
        $manager->flush();
    }
}
