<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240810162953 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE circuito CHANGE ubicacion ubicacion INT NOT NULL, CHANGE nombre descripcion VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE coche RENAME INDEX fk_coche_usuario TO IDX_A1981CD4DB38439E');
        $this->addSql('ALTER TABLE pieza ADD CONSTRAINT FK_D8A7662280D58D71 FOREIGN KEY (motor_id) REFERENCES motor (id)');
        $this->addSql('CREATE INDEX IDX_D8A7662280D58D71 ON pieza (motor_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE circuito CHANGE ubicacion ubicacion VARCHAR(255) NOT NULL, CHANGE descripcion nombre VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE coche RENAME INDEX idx_a1981cd4db38439e TO fk_coche_usuario');
        $this->addSql('ALTER TABLE pieza DROP FOREIGN KEY FK_D8A7662280D58D71');
        $this->addSql('DROP INDEX IDX_D8A7662280D58D71 ON pieza');
    }
}
