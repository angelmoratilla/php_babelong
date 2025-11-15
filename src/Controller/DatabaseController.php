<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DatabaseController extends AbstractController
{
    #[Route('/admin/users', name: 'app_users')]
    public function listUsers(EntityManagerInterface $entityManager): Response
    {
        $userRepository = $entityManager->getRepository(User::class);
        $users = $userRepository->findAll();

        return $this->render('database/users.html.twig', [
            'users' => $users,
            'total_users' => count($users),
        ]);
    }

    #[Route('/admin/database-stats', name: 'app_database_stats')]
    public function databaseStats(EntityManagerInterface $entityManager): Response
    {
        // Obtener estadísticas de la base de datos
        $connection = $entityManager->getConnection();
        
        // Contar usuarios
        $userCount = $connection->fetchOne('SELECT COUNT(*) FROM fw_user');
        
        // Contar palabras
        $wordCount = $connection->fetchOne('SELECT COUNT(*) FROM word');
        
        // Contar frases
        $phraseCount = $connection->fetchOne('SELECT COUNT(*) FROM phrases');
        
        // Obtener información de la base de datos
        $dbName = $connection->fetchOne('SELECT DATABASE()');

        return $this->render('database/stats.html.twig', [
            'database_name' => $dbName,
            'user_count' => $userCount,
            'word_count' => $wordCount,
            'phrase_count' => $phraseCount,
        ]);
    }

    #[Route('/admin/test-connection', name: 'app_test_connection')]
    public function testConnection(EntityManagerInterface $entityManager): Response
    {
        try {
            $connection = $entityManager->getConnection();
            $connection->connect();
            
            if ($connection->isConnected()) {
                return $this->json([
                    'status' => 'success',
                    'message' => 'Conexión a la base de datos establecida correctamente',
                    'database' => $connection->getDatabase(),
                    'driver' => $connection->getDriver()->getName(),
                ]);
            } else {
                return $this->json([
                    'status' => 'error',
                    'message' => 'No se pudo establecer conexión con la base de datos'
                ], 500);
            }
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'Error de conexión: ' . $e->getMessage()
            ], 500);
        }
    }

    #[Route('/admin/admin-test', name: 'app_admin_test')]
    public function testAdminTable(EntityManagerInterface $entityManager): Response
    {
        try {
            $connection = $entityManager->getConnection();
            
            // Verificar estructura de la tabla
            $columns = $connection->fetchAllAssociative('DESCRIBE admin');
            
            return $this->json([
                'status' => 'success',
                'message' => 'Tabla admin creada correctamente',
                'columns' => $columns,
            ]);
        } catch (\Exception $e) {
            return $this->json([
                'status' => 'error',
                'message' => 'Error: ' . $e->getMessage()
            ], 500);
        }
    }
}
