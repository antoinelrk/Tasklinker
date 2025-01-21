<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TasksController extends AbstractController
{
    public function __construct(
        protected UserRepository $userRepository = new UserRepository(),
    ) {}

    #[Route('/tasks/show/{id}', name: 'tasks.show')]
    public function show(Task $task): Response
    {
        return $this->render('tasks/show.html.twig', [
            'task' => $task,
        ]);
    }

    #[Route('/projects/{project}/tasks/create', name: 'tasks.create')]
    public function create(Project $project, Request $request): Response
    {
        dd($request->query->all());
        return $this->render('tasks/create.html.twig', [
            'users' => $this->userRepository->findAll()
        ]);
    }
}
