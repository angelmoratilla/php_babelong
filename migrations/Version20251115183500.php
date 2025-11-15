<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migración para crear la tabla admin
 */
final class Version20251115183500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crea la tabla admin para gestionar administradores del sistema';
    }

    public function up(Schema $schema): void
    {
        // Crear la tabla admin solo si no existe
        $this->addSql('CREATE TABLE IF NOT EXISTS `admin` (
            id INT AUTO_INCREMENT NOT NULL,
            username VARCHAR(255) NOT NULL,
            email VARCHAR(255) NOT NULL,
            password VARCHAR(255) NOT NULL,
            active TINYINT(1) NOT NULL DEFAULT 1,
            created_at DATETIME NOT NULL,
            updated_at DATETIME DEFAULT NULL,
            PRIMARY KEY(id),
            UNIQUE INDEX unique_username (username),
            UNIQUE INDEX unique_email (email)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // Eliminar la tabla admin si se hace rollback
        $this->addSql('DROP TABLE IF EXISTS `admin`');
    }
}
