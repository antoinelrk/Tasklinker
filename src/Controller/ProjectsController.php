<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function create(
        UserRepository $userRepository,
        Request $request,
        EntityManagerInterface $entityManager
    ): Response {
        $project = new Project();
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($project);
            $entityManager->flush();

            return $this->redirectToRoute('projects.show', ['id' => $project->getId()]);
        }

        return $this->render('projects/create.html.twig', [
            'users' => $userRepository->findAll(),
            'form' => $form,
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
