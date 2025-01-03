<?php

namespace App\Controller;

use App\Entity\Project;
use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TasksController extends AbstractController
{
    #[Route('/project/{project}/task/{task}', name: 'task.show')]
    public function index(Project $project, Task $task): Response
    {
        return $this->render('tasks/show.html.twig');
    }
}
