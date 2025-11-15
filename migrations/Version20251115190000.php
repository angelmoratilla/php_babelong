<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Migración para agregar campos created_at y updated_at a la tabla admin
 */
final class Version20251115190000 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Agregar campos created_at y updated_at a la tabla admin';
    }

    public function up(Schema $schema): void
    {
        // Verificar si las columnas ya existen antes de agregarlas
        $this->addSql("
            SET @exist_created_at := (
                SELECT COUNT(*) 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_NAME = 'admin' 
                AND COLUMN_NAME = 'created_at' 
                AND TABLE_SCHEMA = DATABASE()
            );
        ");
        
        $this->addSql("
            SET @sqlstmt_created_at := IF(
                @exist_created_at > 0, 
                'SELECT ''Column created_at already exists''', 
                'ALTER TABLE `admin` ADD created_at DATETIME DEFAULT NULL AFTER active'
            );
        ");
        
        $this->addSql("PREPARE stmt FROM @sqlstmt_created_at;");
        $this->addSql("EXECUTE stmt;");
        $this->addSql("DEALLOCATE PREPARE stmt;");
        
        // Lo mismo para updated_at
        $this->addSql("
            SET @exist_updated_at := (
                SELECT COUNT(*) 
                FROM INFORMATION_SCHEMA.COLUMNS 
                WHERE TABLE_NAME = 'admin' 
                AND COLUMN_NAME = 'updated_at' 
                AND TABLE_SCHEMA = DATABASE()
            );
        ");
        
        $this->addSql("
            SET @sqlstmt_updated_at := IF(
                @exist_updated_at > 0, 
                'SELECT ''Column updated_at already exists''', 
                'ALTER TABLE `admin` ADD updated_at DATETIME DEFAULT NULL AFTER created_at'
            );
        ");
        
        $this->addSql("PREPARE stmt FROM @sqlstmt_updated_at;");
        $this->addSql("EXECUTE stmt;");
        $this->addSql("DEALLOCATE PREPARE stmt;");
        
        // Actualizar registros existentes sin created_at
        $this->addSql("UPDATE `admin` SET created_at = NOW() WHERE created_at IS NULL");
    }

    public function down(Schema $schema): void
    {
        // Eliminar las columnas si se hace rollback
        $this->addSql('ALTER TABLE `admin` DROP COLUMN IF EXISTS updated_at');
        $this->addSql('ALTER TABLE `admin` DROP COLUMN IF EXISTS created_at');
    }
}
