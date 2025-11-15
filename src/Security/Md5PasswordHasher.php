<?php

namespace App\Security;

use Symfony\Component\PasswordHasher\Hasher\CheckPasswordLengthTrait;
use Symfony\Component\PasswordHasher\PasswordHasherInterface;

/**
 * Password hasher para contraseñas MD5 de la base de datos existente
 * Mantiene compatibilidad con el sistema legacy de usuarios
 */
class Md5PasswordHasher implements PasswordHasherInterface
{
    use CheckPasswordLengthTrait;

    public function hash(string $plainPassword): string
    {
        if ($this->isPasswordTooLong($plainPassword)) {
            throw new \InvalidArgumentException('Invalid password.');
        }

        return md5($plainPassword);
    }

    public function verify(string $hashedPassword, string $plainPassword): bool
    {
        if ($this->isPasswordTooLong($plainPassword)) {
            return false;
        }

        return hash_equals($hashedPassword, md5($plainPassword));
    }

    public function needsRehash(string $hashedPassword): bool
    {
        // No necesitamos rehash, mantenemos MD5
        return false;
    }
}
