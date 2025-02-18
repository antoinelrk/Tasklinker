<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(
        protected UserService $userService = new UserService()
    )
    {}

    #[Route('/users/edit/{id}', name: 'users.edit')]
    public function edit(
        User $user,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Si l'utilisateur à des tâches, lui retirer, pareil pour les projets.
            $entityManager->getConnection()->beginTransaction();

            try {
                dd($user->getProjects()->toArray());

            } catch (\Exception $e) {
                $entityManager->getConnection()->rollback();
                throw $e;
            }
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/users/delete/{id}', name: 'users.delete')]
    public function delete(
        User $user,
    ): Response
    {
        $this->userService->delete($user);

        return $this->redirectToRoute('teams');
    }
}
