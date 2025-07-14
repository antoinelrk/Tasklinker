<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Enum\TaskStateEnum;
use App\Form\TaskType;
use App\Repository\UserRepository;
use App\Security\Voter\TaskVoter;
use App\Service\TaskService;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TasksController extends AbstractController
{
    /**
     * Default state for new tasks.
     * This can be overridden by the 'state' query parameter.
     */
    private const DEFAULT_STATE = TaskStateEnum::TODO->value;

    /**
     * TasksController constructor.
     *
     * @param UserRepository $userRepository
     * @param TaskService $taskService
     */
    public function __construct(
        protected UserRepository $userRepository = new UserRepository(),
        protected TaskService $taskService = new TaskService(),
    ) {}

    /**
     * Show task details and allow editing.
     *
     * @param Project $project
     * @param Task $task
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/projects/{project}/tasks/show/{task}', name: 'tasks.show')]
    #[isGranted(TaskVoter::VIEW, subject: 'task')]
    public function show(
        Project $project,
        Task $task,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $entityManager->getConnection()->beginTransaction();

                if ($task->getUser()) {
                    // Attach user to project
                    if (!$project->getUsers()->contains($task->getUser())) {
                        $project->addUser($task->getUser());
                        $entityManager->persist($project);
                    }
                }

                // Add task
                $task->setCreatedAt(new \DateTimeImmutable());
                $entityManager->persist($task);

                // Push in database
                $entityManager->flush();
                $entityManager->getConnection()->commit();

                $entityManager->persist($task);
                $entityManager->flush();
            } catch (\Exception $e) {
                $entityManager->getConnection()->rollback();
                $this->addFlash('error', 'Une erreur est survenue lors de la modification de la tâche.');

                return $this->redirectToRoute('tasks.show', ['project' => $project->getId(), 'task' => $task->getId()]);
            }

            $this->addFlash('success', 'La tâche a bien été modifiée !');

            return $this->redirectToRoute('projects.show', ['id' => $task->getProject()->getId()]);
        }

        return $this->render('tasks/show.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    /**
     * Create a new task for a project.
     *
     * @param Project $project
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws Exception
     */
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
            $entityManager->getConnection()->beginTransaction();

            try {
                if ($task->getUser()) {
                    if (!$project->getUsers()->contains($task->getUser())) {
                        $project->addUser($task->getUser());
                        $entityManager->persist($project);
                    }
                }

                $task->setCreatedAt(new \DateTimeImmutable());
                $entityManager->persist($task);
                $entityManager->flush();
                $entityManager->getConnection()->commit();

                $this->addFlash('success', 'La tâche a bien été ajoutée !');
                return $this->redirectToRoute('projects.show', ['id' => $project->getId()]);
            } catch (\Exception $e) {
                $entityManager->getConnection()->rollback();
                throw $e;
            }
        }

        return $this->render('tasks/create.html.twig', [
            'users' => $this->userRepository->findAll(),
            'project' => $project,
            'state' => $state,
            'form' => $form,
        ]);
    }

    /**
     * Delete a task.
     *
     * @param Task $task
     * @return Response
     */
    #[Route('/tasks/delete/{task}', name: 'task.delete')]
    public function delete(
        Task $task
    ): Response
    {
        $project = $task->getProject();

        if ($this->taskService->delete($task)) {
            $this->addFlash('success', 'La tâche a bien été supprimée !');

            return $this->redirectToRoute('projects.show', ['id' => $project->getId()]);
        }

        $this->addFlash('success', 'La tâche n\'a pu être supprimée');

        return $this->redirectToRoute('task.show', ['id' => $task->getId()]);
    }
}
