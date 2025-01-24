<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Enum\TaskStateEnum;
use App\Form\TaskType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TasksController extends AbstractController
{
    private const DEFAULT_STATE = TaskStateEnum::TODO->value;

    public function __construct(
        protected UserRepository $userRepository = new UserRepository(),
    ) {}

    #[Route('/tasks/show/{id}', name: 'tasks.show')]
    public function show(Task $task): Response
    {
        $project = $task->getProject();

        return $this->render('tasks/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/projects/{project}/tasks/create', name: 'tasks.create')]
    public function create(
        Project $project,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $state = $request->query->get('state') ?? self::DEFAULT_STATE;
        $task = new Task();
        $task->setProject($project);
        $task->setState(TaskStateEnum::getValue($state));

        $form = $this->createForm(TaskType::class, $task, [
            'default_state' => $state
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setCreatedAt(new \DateTimeImmutable());
            $entityManager->persist($task);
            $entityManager->flush();

            $this->addFlash('success', 'La tâche a bien été ajoutée !');

            return $this->redirectToRoute('projects.show', ['id' => $project->getId()]);
        }

        return $this->render('tasks/create.html.twig', [
            'users' => $this->userRepository->findAll(),
            'project' => $project,
            'state' => $state,
            'form' => $form,
        ]);
    }
}
