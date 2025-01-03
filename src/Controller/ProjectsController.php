<?php

namespace App\Controller;

use App\Entity\Project;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectsController extends AbstractController
{
    #[Route('/projects', name: 'projects')]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('projects/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    #[Route('/projects/show/{id}', name: 'projects.show')]
    public function show(Project $project): Response
    {
        dump($project);
        return $this->render('projects/show.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/projects/create', name: 'projects.create')]
    public function create(UserRepository $userRepository): Response
    {
        return $this->render('projects/create.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    #[Route('/projects/edit/{id}', name: 'projects.edit')]
    public function edit(Project $project): Response
    {
        return $this->render('projects/edit.html.twig', [
            'project' => $project,
        ]);
    }

    #[Route('/projects/delete/{id}', name: 'projects.delete')]
    public function delete(Project $project): Response
    {
        return $this->redirectToRoute('home');
    }
}
