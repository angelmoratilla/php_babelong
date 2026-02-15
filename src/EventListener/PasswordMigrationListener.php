<?php

namespace App\EventListener;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

/**
 * Listener que actualiza automáticamente las contraseñas MD5 legacy a bcrypt
 * cuando un usuario inicia sesión exitosamente
 */
#[AsEventListener(event: LoginSuccessEvent::class)]
class PasswordMigrationListener
{
    public function __construct(
        private UserPasswordHasherInterface $passwordHasher,
        private EntityManagerInterface $entityManager
    ) {
    }

    public function __invoke(LoginSuccessEvent $event): void
    {
        $user = $event->getUser();

        // Solo procesar usuarios de tipo User (no Admin)
        if (!$user instanceof User) {
            return;
        }

        $password = $event->getRequest()->getPayload()->get('password');

        // Si no hay contraseña en el request, salir
        if (!$password) {
            return;
        }

        // Verificar si la contraseña necesita ser rehashed (es MD5)
        if ($this->passwordHasher->needsRehash($user)) {
            // Rehash la contraseña a bcrypt
            $hashedPassword = $this->passwordHasher->hashPassword($user, $password);
            $user->setPassword($hashedPassword);
            
            $this->entityManager->flush();
        }
    }
}
