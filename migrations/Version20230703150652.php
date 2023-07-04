<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230703150652 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE event ADD CONSTRAINT FK_3BAE0AA74B84B276 FOREIGN KEY (promoter_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE user ADD site_id INT DEFAULT NULL, ADD last_name VARCHAR(20) DEFAULT NULL, ADD first_name VARCHAR(20) DEFAULT NULL, ADD phone_number VARCHAR(20) NOT NULL, ADD email VARCHAR(180) NOT NULL, ADD admin TINYINT(1) NOT NULL, ADD active TINYINT(1) NOT NULL, ADD password VARCHAR(255) NOT NULL, ADD image VARCHAR(100) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649F6BD1646 FOREIGN KEY (site_id) REFERENCES site (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649F6BD1646 ON user (site_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649F6BD1646');
        $this->addSql('DROP INDEX IDX_8D93D649F6BD1646 ON user');
        $this->addSql('ALTER TABLE user DROP site_id, DROP last_name, DROP first_name, DROP phone_number, DROP email, DROP admin, DROP active, DROP password, DROP image');
        $this->addSql('ALTER TABLE event DROP FOREIGN KEY FK_3BAE0AA74B84B276');
    }
}
