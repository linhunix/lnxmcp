<?php

namespace DoctrineMigrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Migrations for PR-2020
 */
class Version20180228002020 extends AbstractMigration {

    /**
     * @param Schema $schema
     */
    public function up(Schema $schema) {
            $this->addSql("DELETE FROM `products2_product_groups_related` WHERE group_code = '2001_vertical_double' and `products2_product_groups_related`.`link_table_id` = 22 and related_group_code='Samson_S77_Insulated_Roller_Shutter' ");
            $this->addSql("DELETE FROM `products2_product_groups_related` WHERE group_code = '2001_vertical_double' and `products2_product_groups_related`.`link_table_id` = 23 and related_group_code='Samson_S77_Insulated_Roller_Shutter'");
            $this->addSql("DELETE FROM `products2_product_groups_related` WHERE group_code = '2001_vertical_double' and `products2_product_groups_related`.`link_table_id` = 25 and related_group_code='Home_carport_3000_pm'");
//        $this->addSql("UPDATE registrants SET pending = 'no' WHERE pending = ''");
//        $this->addSql("UPDATE registrants SET allergy_pending = 'no' WHERE allergy_pending = ''");
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema) {
        /**
         * Cant roll back
         */
    }

}
