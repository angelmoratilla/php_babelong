<?php

namespace App\Command;

use App\Entity\Admin;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:create-admin',
    description: 'Crea un nuevo administrador',
)]
class CreateAdminCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $helper = $this->getHelper('question');

        $io->title('Crear Nuevo Administrador');

        // Pedir username
        $usernameQuestion = new Question('Username: ');
        $usernameQuestion->setValidator(function ($answer) {
            if (empty($answer)) {
                throw new \RuntimeException('El username no puede estar vacío');
            }
            return $answer;
        });
        $username = $helper->ask($input, $output, $usernameQuestion);

        // Pedir email
        $emailQuestion = new Question('Email: ');
        $emailQuestion->setValidator(function ($answer) {
            if (empty($answer) || !filter_var($answer, FILTER_VALIDATE_EMAIL)) {
                throw new \RuntimeException('Debes proporcionar un email válido');
            }
            
            // Verificar que no exista
            $existing = $this->entityManager->getRepository(Admin::class)
                ->findOneBy(['email' => $answer]);
            if ($existing) {
                throw new \RuntimeException('Ya existe un administrador con este email');
            }
            
            return $answer;
        });
        $email = $helper->ask($input, $output, $emailQuestion);

        // Pedir password
        $passwordQuestion = new Question('Password: ');
        $passwordQuestion->setHidden(true);
        $passwordQuestion->setValidator(function ($answer) {
            if (empty($answer) || strlen($answer) < 6) {
                throw new \RuntimeException('La contraseña debe tener al menos 6 caracteres');
            }
            return $answer;
        });
        $password = $helper->ask($input, $output, $passwordQuestion);

        // Crear el administrador
        $admin = new Admin();
        $admin->setUsername($username);
        $admin->setEmail($email);
        $admin->setActive(true);

        // Hashear la contraseña
        $hashedPassword = $this->passwordHasher->hashPassword($admin, $password);
        $admin->setPassword($hashedPassword);

        // Guardar en la base de datos
        $this->entityManager->persist($admin);
        $this->entityManager->flush();

        $io->success('Administrador creado exitosamente!');
        $io->table(
            ['Campo', 'Valor'],
            [
                ['ID', $admin->getId()],
                ['Username', $admin->getUsername()],
                ['Email', $admin->getEmail()],
                ['Activo', $admin->isActive() ? 'Sí' : 'No'],
            ]
        );

        return Command::SUCCESS;
    }
}
