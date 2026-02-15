<?php

require __DIR__ . '/vendor/autoload.php';

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->loadEnv(__DIR__ . '/.env');

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();
$container = $kernel->getContainer();

$entityManager = $container->get('doctrine.orm.entity_manager');
$passwordHasher = $container->get('security.user_password_hasher');

// Limpiar administradores existentes
$connection = $entityManager->getConnection();
$connection->executeStatement('DELETE FROM admin');

// Crear nuevo administrador
$admin = new App\Entity\Admin();
$admin->setUsername('admin');
$admin->setEmail('admin@babelong.com');
$admin->setActive(true);

$hashedPassword = $passwordHasher->hashPassword($admin, 'admin123');
$admin->setPassword($hashedPassword);

$entityManager->persist($admin);
$entityManager->flush();

echo "✅ Administrador creado exitosamente!\n";
echo "   Email: admin@babelong.com\n";
echo "   Password: admin123\n";
echo "   ID: " . $admin->getId() . "\n";
