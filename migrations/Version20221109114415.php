<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221109114415 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon ADD created_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon ADD updated_by_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3B03A8386 FOREIGN KEY (created_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pokemon ADD CONSTRAINT FK_62DC90F3896DBBDE FOREIGN KEY (updated_by_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_62DC90F3B03A8386 ON pokemon (created_by_id)');
        $this->addSql('CREATE INDEX IDX_62DC90F3896DBBDE ON pokemon (updated_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pokemon DROP CONSTRAINT FK_62DC90F3B03A8386');
        $this->addSql('ALTER TABLE pokemon DROP CONSTRAINT FK_62DC90F3896DBBDE');
        $this->addSql('DROP INDEX IDX_62DC90F3B03A8386');
        $this->addSql('DROP INDEX IDX_62DC90F3896DBBDE');
        $this->addSql('ALTER TABLE pokemon DROP created_by_id');
        $this->addSql('ALTER TABLE pokemon DROP updated_by_id');
    }
}
