<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LandingController extends AbstractController
{
    #[Route('/', name: 'app_landing')]
    public function index(): Response
    {
        return $this->render('landing/index.html.twig');
    }

    #[Route('/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Si el usuario ya está autenticado, redirigir según su rol
        if ($this->getUser()) {
            if ($this->isGranted('ROLE_ADMIN')) {
                return $this->redirectToRoute('app_admin_home');
            }
            return $this->redirectToRoute('app_user_dashboard');
        }

        // Obtener el error de login si existe
        $error = $authenticationUtils->getLastAuthenticationError();
        
        // Último username ingresado
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('landing/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout(): void
    {
        // Este método puede estar vacío, será interceptado por Symfony
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    #[Route('/register', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager
    ): Response
    {
        // Si el usuario ya está autenticado, redirigir
        if ($this->getUser()) {
            return $this->redirectToRoute('app_user_dashboard');
        }

        $error = null;
        $success = false;

        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
            $email = $request->request->get('email');
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            // Validaciones básicas
            if (empty($username) || empty($email) || empty($password)) {
                $error = 'Todos los campos son obligatorios.';
            } elseif ($password !== $confirmPassword) {
                $error = 'Las contraseñas no coinciden.';
            } elseif (strlen($password) < 6) {
                $error = 'La contraseña debe tener al menos 6 caracteres.';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $error = 'El email no es válido.';
            } else {
                // Verificar si el email ya existe
                $existingUser = $entityManager->getRepository(User::class)
                    ->findOneBy(['email' => $email]);
                
                if ($existingUser) {
                    $error = 'El email ya está registrado.';
                } else {
                    // Crear nuevo usuario
                    $user = new User();
                    $user->setUsername($username);
                    $user->setEmail($email);
                    $user->setActive(true);
                    $user->setCreationDate(new \DateTime());
                    $user->setAccessDate(new \DateTime());
                    $user->setHsklevel(1);
                    $user->setLanguage('es');
                    $user->setQuizMode('NORMAL');
                    $user->setUseColors(true);
                    
                    // Hash de la contraseña
                    $hashedPassword = $passwordHasher->hashPassword($user, $password);
                    $user->setPassword($hashedPassword);
                    
                    // Guardar en base de datos
                    $entityManager->persist($user);
                    $entityManager->flush();
                    
                    $success = true;
                }
            }
        }

        return $this->render('landing/register.html.twig', [
            'error' => $error,
            'success' => $success,
        ]);
    }
}
