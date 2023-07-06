<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230705134204 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234491D1EFD');
        $this->addSql('ALTER TABLE place DROP FOREIGN KEY FK_741D53CD491D1EFD');
        $this->addSql('ALTER TABLE add_event DROP FOREIGN KEY FK_861D006671F7E88B');
        $this->addSql('DROP TABLE add_event');
        $this->addSql('DROP INDEX IDX_2D5B0234491D1EFD ON city');
        $this->addSql('ALTER TABLE city DROP add_event_id');
        $this->addSql('DROP INDEX IDX_741D53CD491D1EFD ON place');
        $this->addSql('ALTER TABLE place DROP add_event_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE add_event (id INT AUTO_INCREMENT NOT NULL, event_id INT NOT NULL, UNIQUE INDEX UNIQ_861D006671F7E88B (event_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE add_event ADD CONSTRAINT FK_861D006671F7E88B FOREIGN KEY (event_id) REFERENCES event (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE city ADD add_event_id INT NOT NULL');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234491D1EFD FOREIGN KEY (add_event_id) REFERENCES add_event (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_2D5B0234491D1EFD ON city (add_event_id)');
        $this->addSql('ALTER TABLE place ADD add_event_id INT NOT NULL');
        $this->addSql('ALTER TABLE place ADD CONSTRAINT FK_741D53CD491D1EFD FOREIGN KEY (add_event_id) REFERENCES add_event (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_741D53CD491D1EFD ON place (add_event_id)');
    }
}
