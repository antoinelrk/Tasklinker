<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/users/edit/{id}', name: 'users.edit')]
    public function edit(User $user): Response
    {
        return $this->render('user/edit.html.twig');
    }

    #[Route('/users/delete/{id}', name: 'users.delete')]
    public function delete(User $user): Response
    {
        return $this->redirectToRoute('teams');
    }
}
