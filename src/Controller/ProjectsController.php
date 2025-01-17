<?php

namespace App\Controller;

use App\Entity\Project;
use App\Form\ProjectType;
use App\Repository\ProjectRepository;
use App\Repository\UserRepository;
use App\Service\ProjectService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class ProjectsController extends AbstractController
{
    public function __construct(
        private readonly ProjectService $projectService = new ProjectService(),
    ) {}

    /**
     * List projects
     *
     * @param ProjectRepository $projectRepository
     *
     * @return Response
     */
    #[Route('/projects', name: 'projects')]
    public function index(ProjectRepository $projectRepository): Response
    {
        return $this->render('projects/index.html.twig', [
            'projects' => $projectRepository->findAll(),
        ]);
    }

    /**
     * Show project
     *
     * @param Project $project
     *
     * @return Response
     */
    #[Route('/projects/show/{id}', name: 'projects.show')]
    public function show(Project $project): Response
    {
        dump($project);
        return $this->render('projects/show.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * Create new project
     *
     * @param UserRepository $userRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
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

            $this->addFlash('success', 'Projet créé avec succès !');

            return $this->redirectToRoute('projects.show', ['id' => $project->getId()]);
        }

        return $this->render('projects/create.html.twig', [
            'users' => $userRepository->findAll(),
            'form' => $form,
        ]);
    }

    /**
     * Show edition form
     *
     * @param Project $project
     *
     * @return Response
     */
    #[Route('/projects/edit/{id}', name: 'projects.edit')]
    public function edit(
        Request $request,
        Project $project,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $form = $this->createForm(ProjectType::class, $project);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            $this->addFlash('success', 'Projet modifié avec succès !');

            return $this->redirectToRoute('projects.show', ['id' => $project->getId()]);
        }

        return $this->render('projects/edit.html.twig', [
            'project' => $project,
            'form' => $form,
        ]);
    }

    /**
     * Delete a project
     *
     * @param Project $project
     *
     * @return Response
     */
    #[Route('/projects/delete/{id}', name: 'projects.delete')]
    public function delete(Project $project): Response
    {
        if ($this->projectService->delete($project)) {
            return $this->redirectToRoute('home');
        }

        // TODO: Faire une redirection (type: back()) et ajouter un flash message
        return $this->redirectToRoute('home');
    }
}
