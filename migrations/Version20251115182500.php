<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migración para crear la tabla admin
 */
final class Version20251115182500 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Crear tabla admin para gestionar administradores del sistema';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE `admin` (
            id INT AUTO_INCREMENT NOT NULL, 
            username VARCHAR(255) NOT NULL, 
            email VARCHAR(255) NOT NULL, 
            password VARCHAR(255) NOT NULL, 
            active TINYINT(1) NOT NULL DEFAULT 1, 
            PRIMARY KEY(id),
            UNIQUE KEY unique_username (username),
            UNIQUE KEY unique_email (email)
        ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE `admin`');
    }
}
