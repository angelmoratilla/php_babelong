<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/user')]
#[IsGranted('ROLE_USER')]
class UserAreaController extends AbstractController
{
    #[Route('/dashboard', name: 'app_user_dashboard')]
    public function dashboard(): Response
    {
        $user = $this->getUser();
        
        return $this->render('user/dashboard.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/vocabulary', name: 'app_user_vocabulary')]
    public function vocabulary(): Response
    {
        $user = $this->getUser();
        
        return $this->render('user/vocabulary.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/exercises', name: 'app_user_exercises')]
    public function exercises(): Response
    {
        $user = $this->getUser();
        
        return $this->render('user/exercises.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/progress', name: 'app_user_progress')]
    public function progress(): Response
    {
        $user = $this->getUser();
        
        return $this->render('user/progress.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profile', name: 'app_user_profile')]
    public function profile(): Response
    {
        $user = $this->getUser();
        
        return $this->render('user/profile.html.twig', [
            'user' => $user,
        ]);
    }
}
