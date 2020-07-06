<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200628135310 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D5E5C27E9');
        $this->addSql('ALTER TABLE commande ADD traite TINYINT(1) NOT NULL, DROP id_restaurant');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD id_restaurant INT NOT NULL, DROP traite');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D5E5C27E9 FOREIGN KEY (iduser) REFERENCES user (id)');
    }
}
