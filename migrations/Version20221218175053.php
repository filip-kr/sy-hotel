<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221218175053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, guest_id INT NOT NULL, overnight_stay_id INT DEFAULT NULL, sign_in_date DATETIME NOT NULL, sign_out_date DATETIME DEFAULT NULL, INDEX IDX_42C849559A4AA658 (guest_id), UNIQUE INDEX UNIQ_42C8495537E9DF48 (overnight_stay_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849559A4AA658 FOREIGN KEY (guest_id) REFERENCES guest (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C8495537E9DF48 FOREIGN KEY (overnight_stay_id) REFERENCES overnight_stay (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849559A4AA658');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C8495537E9DF48');
        $this->addSql('DROP TABLE reservation');
    }
}
