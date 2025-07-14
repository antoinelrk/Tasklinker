<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Service\UserService;
use DateTimeImmutable;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class UserController extends AbstractController
{
    public function __construct(
        protected UserService $userService = new UserService()
    )
    {}

    /**
     * Create a new user.
     *
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     * @throws Exception
     */
    #[Route('/users/edit/{id}', name: 'users.edit')]
    #[isGranted('ROLE_ADMIN')]
    public function edit(
        User $user,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->getConnection()->beginTransaction();

            try {
                $user->setName($form->get('name')->getData());
                $user->setFirstName($form->get('first_name')->getData());
                $user->setEmail($form->get('email')->getData());
                $user->setEmploymentStartedAt(
                    DateTimeImmutable::createFromInterface($form->get('employment_started_at')->getData())
                );
                $user->setEmploymentContract($form->get('employment_contract')->getData());
                $user->setRoles($form->get('roles')->getData());
                $user->setUpdatedAt(new DateTimeImmutable());
                $entityManager->persist($user);
                $entityManager->flush();

                $entityManager->getConnection()->commit();

                return $this->redirectToRoute('teams');

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

    /**
     * Delete a user.
     *
     * @param User $user
     * @return Response
     */
    #[Route('/users/delete/{id}', name: 'users.delete')]
    #[isGranted('ROLE_ADMIN')]
    public function delete(
        User $user,
    ): Response
    {
        $this->userService->delete($user);

        return $this->redirectToRoute('teams');
    }
}
