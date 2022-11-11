<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220930005510 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $exists = $this->connection->fetchColumn("SELECT COUNT(*) FROM dtb_category WHERE category_name = 'SE'");
        if ($exists == 0) {
            $this->addSql("INSERT INTO `dtb_category` (`parent_category_id`, `creator_id`, `category_name`, `hierarchy`, `sort_no`, `create_date`, `update_date`, `seo_title`, `seo_author`, `seo_description`, `seo_keyword`, `seo_meta_robots`, `seo_meta_tags`, `page_text`, `discriminator_type`) VALUES (NULL, 1, 'SE', 1, 28, '2022-09-12 05:59:23', '2022-09-12 05:59:23', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'category');");
        }
        $exists = $this->connection->fetchColumn("SELECT COUNT(*) FROM dtb_category WHERE category_name = 'SE001'");
        if ($exists == 0) {
            $this->addSql("INSERT INTO `dtb_category` (`parent_category_id`, `creator_id`, `category_name`, `hierarchy`, `sort_no`, `create_date`, `update_date`, `seo_title`, `seo_author`, `seo_description`, `seo_keyword`, `seo_meta_robots`, `seo_meta_tags`, `page_text`, `discriminator_type`) VALUES (NULL, 1, 'SE001', 1, 29, '2022-09-20 08:39:09', '2022-09-20 08:39:09', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'category');");
        }
        $exists = $this->connection->fetchColumn("SELECT COUNT(*) FROM dtb_category WHERE category_name = '防炎'");
        if ($exists == 0) {
            $this->addSql("INSERT INTO `dtb_category` (`parent_category_id`, `creator_id`, `category_name`, `hierarchy`, `sort_no`, `create_date`, `update_date`, `seo_title`, `seo_author`, `seo_description`, `seo_keyword`, `seo_meta_robots`, `seo_meta_tags`, `page_text`, `discriminator_type`) VALUES (NULL, 1, '防炎', 1, 30, '2022-09-30 00:39:32', '2022-09-30 00:39:32', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'category');");
        }

    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
