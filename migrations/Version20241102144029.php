<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241102144029 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking CHANGE service_id service_id INT DEFAULT NULL, CHANGE user_id user_id INT DEFAULT NULL, CHANGE date_stop date_end DATETIME NOT NULL');
        $this->addSql('ALTER TABLE service DROP duration');
        $this->addSql('ALTER TABLE user ADD first_name VARCHAR(255) NOT NULL, ADD last_name VARCHAR(255) NOT NULL, DROP firstname, DROP lastname');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE booking CHANGE service_id service_id INT NOT NULL, CHANGE user_id user_id INT NOT NULL, CHANGE date_end date_stop DATETIME NOT NULL');
        $this->addSql('ALTER TABLE service ADD duration INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD firstname VARCHAR(255) NOT NULL, ADD lastname VARCHAR(255) NOT NULL, DROP first_name, DROP last_name');
    }
}
