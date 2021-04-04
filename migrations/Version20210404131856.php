<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210404131856 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_entity ADD thumb_path_name VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE image_entity ADD CONSTRAINT FK_A1351AA04E7AF8F FOREIGN KEY (gallery_id) REFERENCES gallery (id)');
        $this->addSql('CREATE INDEX IDX_A1351AA04E7AF8F ON image_entity (gallery_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE image_entity DROP FOREIGN KEY FK_A1351AA04E7AF8F');
        $this->addSql('DROP INDEX IDX_A1351AA04E7AF8F ON image_entity');
        $this->addSql('ALTER TABLE image_entity DROP thumb_path_name');
    }
}
