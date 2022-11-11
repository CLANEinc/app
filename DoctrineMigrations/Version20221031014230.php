<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221031014230 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql("UPDATE dtb_tax_rule SET tax_rate = 0 WHERE id = 1");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql("UPDATE dtb_tax_rule SET tax_rate = 10 WHERE id = 1");
    }
}
