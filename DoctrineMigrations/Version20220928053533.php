<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220928053533 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        $this->addSql('DELETE FROM dtb_csv WHERE csv_type_id = 3');

        $this->addSql("
            INSERT INTO dtb_csv (csv_type_id, entity_name, field_name, reference_field_name, disp_name, sort_no, enabled, create_date, update_date, discriminator_type) VALUES
            (3, 'Fixed', '9999', NULL, '店舗名', 1, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\OrderItem', 'denpyo_no', NULL, '伝票番号', 2, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Order', 'order_no',  NULL, '受注番号', 3, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\OrderItem', 'meisai_no', NULL, '明細行番号', 4, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Order', 'order_date', NULL, '受注日', 5, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Order', 'payment_method', NULL, '支払方法', 6, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Shipping', 'shipping_delivery_date', NULL, '配達希望日', 7, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Shipping', 'shipping_delivery_time', NULL, '時間指定', 8, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Order', 'total', NULL, '総合計', 9, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Order', 'subtotal', NULL, '商品計', 10, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Order', 'inner_tax', NULL, '税金', 11, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Order', 'delivery_fee_total', NULL, '発送代', 12, 1, now(), now(), 'csv'),
            (3, 'Fixed', '0', NULL, '手数料', 13, 1, now(), now(), 'csv'),
            (3, 'Fixed', '0', NULL, '他費用', 14, 1, now(), now(), 'csv'),
            (3, 'Fixed', '0', NULL, 'ポイント', 15, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Shipping', 'FullName', NULL, '送り先名', 16, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Shipping', 'postal_code', NULL, '送り先郵便番号', 17, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\OrderItem', 'ShippingFullAddr', NULL, '送り先住所1', 18, 1, now(), now(), 'csv'),
            (3, 'Fixed', '', NULL, '送り先住所2', 19, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Shipping', 'phone_number', NULL, '送り先電話番号', 20, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Shipping', 'note', NULL, '備考', 21, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\OrderItem', 'Product', 'id', '商品コード', 22, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\OrderItem', 'product_name', NULL, '自社品番', 23, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\OrderItem', 'product_kubun', NULL, '商品区分', 24, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\OrderItem', 'product_name', NULL, '商品名', 25, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\OrderItem', 'OptionChoiceCSV', NULL, '商品OP', 26, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\OrderItem', 'quantity', NULL, '受注数', 27, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\OrderItem', 'price', NULL, '売単価', 28, 1, now(), now(), 'csv'),
            (3, 'Fixed', '自社品', NULL, '仕入先名', 29, 1, now(), now(), 'csv'),
            (3, 'Fixed', '', NULL, '営業スタッフ', 30, 1, now(), now(), 'csv'),
            (3, 'Eccube\\\\Entity\\\\Shipping', 'shipping_date', NULL, '出荷日', 31, 1, now(), now(), 'csv')
        ");
    }

    public function down(Schema $schema) : void
    {
        $this->addSql('DELETE FROM dtb_csv WHERE csv_type_id = 3');
        $this->addSql("
            INSERT INTO `dtb_csv` (`id`, `csv_type_id`, `creator_id`, `entity_name`, `field_name`, `reference_field_name`, `disp_name`, `sort_no`, `enabled`, `create_date`, `update_date`, `discriminator_type`) VALUES
            (127, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'mail_send_date', NULL, '出荷メール送信日', 71, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (126, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'note', NULL, '配達用メモ', 70, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (125, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'tracking_number', NULL, '出荷伝票番号', 69, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (124, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'shipping_date', NULL, '発送日', 68, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (123, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'shipping_delivery_fee', NULL, '送料', 67, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (122, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'DeliveryFee', 'id', '送料ID', 66, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (121, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'shipping_delivery_date', NULL, 'お届け希望日', 65, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (120, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'shipping_delivery_time', NULL, 'お届け時間(名称)', 64, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (119, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'DeliveryTime', 'id', 'お届け時間ID', 63, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (118, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'shipping_delivery_name', NULL, '配送業者(名称)', 62, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (117, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'Delivery', 'id', '配送業者(ID)', 61, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (116, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'phone_number', NULL, '配送先_TEL', 60, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (115, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'addr02', NULL, '配送先_住所2', 59, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (114, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'addr01', NULL, '配送先_住所1', 58, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (113, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'Pref', 'name', '配送先_都道府県(名称)', 57, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (112, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'Pref', 'id', '配送先_都道府県(ID)', 56, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (111, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'postal_code', NULL, '配送先_郵便番号', 55, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (110, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'company_name', NULL, '配送先_会社名', 54, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (109, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'kana02', NULL, '配送先_お名前(メイ)', 53, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (108, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'kana01', NULL, '配送先_お名前(セイ)', 52, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (107, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'name02', NULL, '配送先_お名前(名)', 51, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (106, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'name01', NULL, '配送先_お名前(姓)', 50, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (105, 3, NULL, 'Eccube\\\\Entity\\\\Shipping', 'id', NULL, '出荷ID', 49, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (104, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'OrderItemType', 'name', '明細区分(名称)', 48, 0, '2018-07-23 09:00:00', '2018-07-23 09:00:00', 'csv'),
            (103, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'OrderItemType', 'id', '明細区分(ID)', 47, 0, '2018-07-23 09:00:00', '2018-07-23 09:00:00', 'csv'),
            (102, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'tax_rule', NULL, '税率ルール(ID)', 46, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (101, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'tax_rate', NULL, '税率', 45, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (100, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'quantity', NULL, '個数', 44, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (99, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'price', NULL, '価格', 43, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (98, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'class_category_name2', NULL, '規格分類名2', 42, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (97, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'class_category_name1', NULL, '規格分類名1', 41, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (96, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'class_name2', NULL, '規格名2', 40, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (95, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'class_name1', NULL, '規格名1', 39, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (94, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'product_code', NULL, '商品コード', 38, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (93, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'product_name', NULL, '商品名', 37, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (92, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'ProductClass', 'id', '商品規格ID', 36, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (91, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'Product', 'id', '商品ID', 35, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (90, 3, NULL, 'Eccube\\\\Entity\\\\OrderItem', 'id', NULL, '注文詳細ID', 34, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (89, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'payment_date', NULL, '入金日', 33, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (88, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'order_date', NULL, '受注日', 32, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (87, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'payment_method', NULL, '支払方法(名称)', 31, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (86, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'Payment', 'id', '支払方法(ID)', 30, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (85, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'OrderStatus', 'name', '対応状況(名称)', 29, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (84, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'OrderStatus', 'id', '対応状況(ID)', 28, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (83, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'payment_total', NULL, '支払合計', 27, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (82, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'total', NULL, '合計', 26, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (81, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'tax', NULL, '税金', 25, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (80, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'delivery_fee_total', NULL, '送料', 24, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (79, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'discount', NULL, '値引き', 23, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (78, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'subtotal', NULL, '小計', 22, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (77, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'note', NULL, 'ショップ用メモ欄', 21, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (76, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'birth', NULL, '誕生日', 20, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (75, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'Job', 'name', '職業(名称)', 19, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (74, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'Job', 'id', '職業(ID)', 18, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (73, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'Sex', 'name', '性別(名称)', 17, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (72, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'Sex', 'id', '性別(ID)', 16, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (71, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'phone_number', NULL, 'TEL', 15, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (70, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'email', NULL, 'メールアドレス', 14, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (69, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'addr02', NULL, '住所2', 13, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (68, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'addr01', NULL, '住所1', 12, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (67, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'Pref', 'name', '都道府県(名称)', 11, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (66, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'Pref', 'id', '都道府県(ID)', 10, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (65, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'postal_code', NULL, '郵便番号', 9, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (64, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'company_name', NULL, '会社名', 8, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (63, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'kana02', NULL, 'お名前(メイ)', 7, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (62, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'kana01', NULL, 'お名前(セイ)', 6, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (61, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'name02', NULL, 'お名前(名)', 5, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (60, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'name01', NULL, 'お名前(姓)', 4, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (59, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'Customer', 'id', '会員ID', 3, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (58, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'order_no', NULL, '注文番号', 2, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv'),
            (57, 3, NULL, 'Eccube\\\\Entity\\\\Order', 'id', NULL, '注文ID', 1, 0, '2017-03-07 10:14:00', '2017-03-07 10:14:00', 'csv')
        ");
    }
}
