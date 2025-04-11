<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250411095909 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE hit (id INT AUTO_INCREMENT NOT NULL, image_id VARCHAR(32) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_5AD226413DA5256D (image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE hit ADD CONSTRAINT FK_5AD226413DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image CHANGE id id VARCHAR(32) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image_public CHANGE id id VARCHAR(32) NOT NULL
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE hit DROP FOREIGN KEY FK_5AD226413DA5256D
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE hit
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image CHANGE id id VARCHAR(255) NOT NULL
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE image_public CHANGE id id BINARY(16) NOT NULL COMMENT '(DC2Type:uuid)'
        SQL);
    }
}
