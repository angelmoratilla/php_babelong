<?php

namespace App\Controller;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

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
    public function testCreate(EntityManagerInterface $entityManager): Response
    {
        // Crear un administrador de prueba
        $admin = new Admin();
        $admin->setUsername('admin_test');
        $admin->setEmail('admin@example.com');
        $admin->setPassword(password_hash('test123', PASSWORD_BCRYPT));
        $admin->setActive(true);

        $entityManager->persist($admin);
        $entityManager->flush();

        return $this->json([
            'status' => 'success',
            'message' => 'Administrador de prueba creado correctamente',
            'admin' => [
                'id' => $admin->getId(),
                'username' => $admin->getUsername(),
                'email' => $admin->getEmail(),
                'active' => $admin->isActive(),
            ]
        ]);
    }
}
