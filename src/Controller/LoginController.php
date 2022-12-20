<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {        
        $error = $authenticationUtils->getLastAuthenticationError();
        
        return $this->render('login/login.html.twig', [
            'error' => $error
        ]);
    }

    #[Route('/logout', name: 'logout')]
    public function logout(): void
    {
    }
}
