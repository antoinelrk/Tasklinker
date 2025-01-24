<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class TeamsController extends AbstractController
{
    #[Route('/teams', name: 'teams')]
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('teams/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }
}
