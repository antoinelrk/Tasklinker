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
     * Create new project
     *
     * @param Request $request
     * @param UserRepository $userRepository
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    #[Route('/projects/create', name: 'projects.create')]
    public function create(
        Request $request,
        UserRepository $userRepository,
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
     * List projects
     *
     * @param ProjectRepository $projectRepository
     *
     * @return Response
     */
    #[Route('/projects', name: 'projects')]
    public function index(ProjectRepository $projectRepository): Response
    {
        $projects = $projectRepository->current();

        return $this->render('projects/index.html.twig', [
            'projects' => $projects,
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
        return $this->render('projects/show.html.twig', [
            'project' => $project,
        ]);
    }

    /**
     * Show edition form
     *
     * @param Request $request
     * @param Project $project
     * @param EntityManagerInterface $entityManager
     *
     * @return Response
     */
    #[Route('/projects/edit/{project}', name: 'projects.edit')]
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
    #[Route('/projects/delete/{project}', name: 'projects.delete')]
    public function delete(Project $project): Response
    {
        if ($this->projectService->archive($project)) {
            $this->addFlash('success', 'Projet supprimé');

            return $this->redirectToRoute('home');
        }

        $this->addFlash('error', 'Le projet n\'a pu être supprimé');

        return $this->redirectToRoute('home');
    }
}
