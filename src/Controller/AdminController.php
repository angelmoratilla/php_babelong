<?php

namespace App\Controller;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AdminController extends AbstractController
{
    #[Route('/admin/admins', name: 'app_admins')]
    public function listAdmins(EntityManagerInterface $entityManager): Response
    {
        $adminRepository = $entityManager->getRepository(Admin::class);
        $admins = $adminRepository->findAll();

        return $this->render('admin/list.html.twig', [
            'admins' => $admins,
            'total_admins' => count($admins),
        ]);
    }

    #[Route('/admin/admin/test-create', name: 'app_admin_test_create')]
    public function testCreate(
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response
    {
        // Verificar si ya existe
        $existing = $entityManager->getRepository(Admin::class)
            ->findOneBy(['email' => 'admin@babelong.com']);
        
        if ($existing) {
            return $this->json([
                'status' => 'info',
                'message' => 'El administrador ya existe',
                'admin' => [
                    'id' => $existing->getId(),
                    'username' => $existing->getUsername(),
                    'email' => $existing->getEmail(),
                ]
            ]);
        }

        // Crear un administrador de prueba
        $admin = new Admin();
        $admin->setUsername('admin');
        $admin->setEmail('admin@babelong.com');
        $admin->setActive(true);
        
        // Hashear la contraseña correctamente
        $hashedPassword = $passwordHasher->hashPassword($admin, 'admin123');
        $admin->setPassword($hashedPassword);

        $entityManager->persist($admin);
        $entityManager->flush();

        return $this->json([
            'status' => 'success',
            'message' => 'Administrador creado correctamente',
            'credentials' => [
                'email' => 'admin@babelong.com',
                'password' => 'admin123'
            ],
            'admin' => [
                'id' => $admin->getId(),
                'username' => $admin->getUsername(),
                'email' => $admin->getEmail(),
                'active' => $admin->isActive(),
            ]
        ]);
    }
}
