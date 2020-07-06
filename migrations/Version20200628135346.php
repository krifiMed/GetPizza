<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200628135346 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande ADD idrestaurant INT DEFAULT NULL');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D5E5C27E9 FOREIGN KEY (iduser) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67D263C0F05 FOREIGN KEY (idrestaurant) REFERENCES restaurant (id)');
        $this->addSql('CREATE INDEX IDX_6EEAA67D263C0F05 ON commande (idrestaurant)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D5E5C27E9');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67D263C0F05');
        $this->addSql('DROP INDEX IDX_6EEAA67D263C0F05 ON commande');
        $this->addSql('ALTER TABLE commande DROP idrestaurant');
    }
}
