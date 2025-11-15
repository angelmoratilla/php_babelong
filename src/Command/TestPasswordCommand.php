<?php

namespace App\Command;

use App\Entity\Admin;
use App\Entity\User;
use App\Security\Md5PasswordHasher;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

#[AsCommand(
    name: 'app:test-password',
    description: 'Probar verificación de contraseñas',
)]
class TestPasswordCommand extends Command
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private Md5PasswordHasher $md5Hasher
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('email', InputArgument::REQUIRED, 'Email del usuario o admin')
            ->addArgument('password', InputArgument::REQUIRED, 'Contraseña a probar');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $email = $input->getArgument('email');
        $password = $input->getArgument('password');

        // Buscar admin
        $admin = $this->entityManager->getRepository(Admin::class)->findOneBy(['email' => $email]);
        if ($admin) {
            $io->info('Usuario encontrado: Admin');
            $io->info('Password hash: ' . $admin->getPassword());
            
            $isValid = $this->passwordHasher->isPasswordValid($admin, $password);
            
            if ($isValid) {
                $io->success('✅ Contraseña correcta para admin!');
            } else {
                $io->error('❌ Contraseña incorrecta para admin');
            }
            
            return Command::SUCCESS;
        }

        // Buscar usuario
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if ($user) {
            $io->info('Usuario encontrado: User');
            $io->info('Password hash: ' . $user->getPassword());
            $io->info('MD5 de la contraseña ingresada: ' . md5($password));
            
            // Probar con el hasher de Symfony
            $isValid = $this->passwordHasher->isPasswordValid($user, $password);
            
            if ($isValid) {
                $io->success('✅ Contraseña correcta con Symfony hasher!');
            } else {
                $io->error('❌ Contraseña incorrecta con Symfony hasher');
                
                // Probar directamente con MD5
                $directCheck = $this->md5Hasher->verify($user->getPassword(), $password);
                if ($directCheck) {
                    $io->warning('⚠️  La contraseña es correcta con MD5 directo, hay un problema de configuración');
                } else {
                    $io->error('❌ Contraseña incorrecta también con MD5 directo');
                }
            }
            
            return Command::SUCCESS;
        }

        $io->error('Usuario no encontrado');
        return Command::FAILURE;
    }
}
