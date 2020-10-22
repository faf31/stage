<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201006074003 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annonces_players (annonces_id INT NOT NULL, players_id INT NOT NULL, INDEX IDX_7B8BDDAE4C2885D7 (annonces_id), INDEX IDX_7B8BDDAEF1849495 (players_id), PRIMARY KEY(annonces_id, players_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE annonces_players ADD CONSTRAINT FK_7B8BDDAE4C2885D7 FOREIGN KEY (annonces_id) REFERENCES annonces (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE annonces_players ADD CONSTRAINT FK_7B8BDDAEF1849495 FOREIGN KEY (players_id) REFERENCES players (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE annonces_players');
    }
}
