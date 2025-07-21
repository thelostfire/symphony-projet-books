<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250721122420 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6F675F31B');
        $this->addSql('DROP INDEX IDX_794381C6F675F31B ON review');
        $this->addSql('ALTER TABLE review CHANGE author_id commenter_id INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6B4D5A9E2 FOREIGN KEY (commenter_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_794381C6B4D5A9E2 ON review (commenter_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE review DROP FOREIGN KEY FK_794381C6B4D5A9E2');
        $this->addSql('DROP INDEX IDX_794381C6B4D5A9E2 ON review');
        $this->addSql('ALTER TABLE review CHANGE commenter_id author_id INT NOT NULL');
        $this->addSql('ALTER TABLE review ADD CONSTRAINT FK_794381C6F675F31B FOREIGN KEY (author_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_794381C6F675F31B ON review (author_id)');
    }
}
