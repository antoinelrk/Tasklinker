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
use Symfony\Component\Security\Http\Attribute\IsGranted;

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
        $projects = $this->isGranted('ROLE_ADMIN') ?
            $projectRepository->findAll() :
            $projectRepository->current();

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
        // Check if the user is an admin
        if ($this->isGranted('ROLE_ADMIN')) {
            return $this->render('projects/show.html.twig', [
                'project' => $project,
            ]);
        }

        // If the user is not an admin, check if they are part of the project
        if ($this->isGranted('ROLE_USER') && $project->getUsers()->contains($this->getUser())) {
            return $this->render('projects/show.html.twig', [
                'project' => $project,
            ]);
        }

        return $this->redirectToRoute('projects');
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
    #[isGranted('ROLE_ADMIN')]
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
    #[isGranted('ROLE_ADMIN')]
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
