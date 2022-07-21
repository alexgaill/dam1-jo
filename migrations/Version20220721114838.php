<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220721114838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TEMPORARY TABLE __temp__athlete AS SELECT id, nom, prenom, date_naissance, photo FROM athlete');
        $this->addSql('DROP TABLE athlete');
        $this->addSql('CREATE TABLE athlete (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, pays_id INTEGER NOT NULL, discipline_id INTEGER NOT NULL, nom VARCHAR(65) NOT NULL, prenom VARCHAR(65) NOT NULL, date_naissance DATE NOT NULL, photo VARCHAR(40) DEFAULT NULL, CONSTRAINT FK_C03B8321A6E44244 FOREIGN KEY (pays_id) REFERENCES pays (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_C03B8321A5522701 FOREIGN KEY (discipline_id) REFERENCES discipline (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO athlete (id, nom, prenom, date_naissance, photo) SELECT id, nom, prenom, date_naissance, photo FROM __temp__athlete');
        $this->addSql('DROP TABLE __temp__athlete');
        $this->addSql('CREATE INDEX IDX_C03B8321A6E44244 ON athlete (pays_id)');
        $this->addSql('CREATE INDEX IDX_C03B8321A5522701 ON athlete (discipline_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_C03B8321A6E44244');
        $this->addSql('DROP INDEX IDX_C03B8321A5522701');
        $this->addSql('CREATE TEMPORARY TABLE __temp__athlete AS SELECT id, nom, prenom, date_naissance, photo FROM athlete');
        $this->addSql('DROP TABLE athlete');
        $this->addSql('CREATE TABLE athlete (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, nom VARCHAR(65) NOT NULL, prenom VARCHAR(65) NOT NULL, date_naissance DATE NOT NULL, photo VARCHAR(40) DEFAULT NULL)');
        $this->addSql('INSERT INTO athlete (id, nom, prenom, date_naissance, photo) SELECT id, nom, prenom, date_naissance, photo FROM __temp__athlete');
        $this->addSql('DROP TABLE __temp__athlete');
    }
}
