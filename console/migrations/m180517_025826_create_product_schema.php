<?php

use yii\db\Migration;

/**
 * Class m180517_025826_create_product_schema
 */
class m180517_025826_create_product_schema extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /* Game table */
        $this->createTable('{{%game}}', [
            'id' => $this->primaryKey(),
            'sku' => $this->string(50),
            'title' => $this->string(100)->notNull(),
            'short_title' => $this->string(100),
            'slug' => $this->string(100)->notNull()->unique(),
            'excerpt' => $this->string(200),
            'unit_name' => $this->string(50),
            'content' => $this->text()->notNull(),
            'image_id' => $this->integer(),
            'price' => $this->integer(),
            'reseller_price' => $this->integer(),
            'reseller_price_amplitude' => $this->float(),
            'original_price' => $this->integer(),
            'expected_profit' => $this->float(), //usd
            'pack' => $this->integer()->notNull()->defaultValue(1),
            'promotion_info' => $this->string(255),
            'event_info' => $this->string(255),
            'meta_title' => $this->string(160),
            'meta_keyword' => $this->string(160),
            'meta_description' => $this->string(160),
            'status' => $this->string()->comment('Enum: Y,N,D')->defaultValue('Y')->notNull(),
            'pin' => $this->integer()->defaultValue(0),
            'hot_deal' => $this->integer()->defaultValue(0),
            'top_grossing' => $this->integer()->defaultValue(0),
            'new_trending' => $this->integer()->defaultValue(0),
            'back_to_stock' => $this->integer()->defaultValue(0),
            'group_id' => $this->integer(),
            'method' => $this->integer(),
            'version' => $this->integer(),
            'package' => $this->integer(),
            'min_quantity' => $this->integer(),
            'created_at' => $this->dateTime(),            
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->dateTime(),
            'deleted_by' => $this->integer(),
            'change_price_request_code' => $this->string(50),
            'change_price_request_time' => $this->dateTime(),            
            'price1' => $this->float(),
            'price2' => $this->float(),
            'price3' => $this->float(),
            'average_speed' => $this->integer(), // seconds
            'number_supplier' => $this->integer(),
            'remark' => $this->text(),
            'price_remark' => $this->text(),
            'google_ads' => $this->text(),
            'auto_dispatcher' => $this->integer(4)->notNull()->defaultValue(0),
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%game}} MODIFY `status` ENUM('Y', 'N', 'D') NOT NULL DEFAULT 'Y'";
            $command = $this->db->createCommand($alter);
            $command->execute();
            $gameIndex = "CREATE INDEX index_auto_dispatcher ON {{%game}} (`auto_dispatcher`)";
            $this->db->createCommand($gameIndex)->execute();
        }

        $this->createTable('{{%game_image}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'image_id' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createTable('{{%game_price_log}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'old_price' => $this->float()->defaultValue(0),
            'new_price' => $this->float()->defaultValue(0),

            'old_price_1' => $this->float()->defaultValue(0),
            'old_price_2' => $this->float()->defaultValue(0),
            'old_price_3' => $this->float()->defaultValue(0),

            'new_price_1' => $this->float()->defaultValue(0),
            'new_price_2' => $this->float()->defaultValue(0),
            'new_price_3' => $this->float()->defaultValue(0),

            'old_reseller_1' => $this->float()->defaultValue(0),
            'new_reseller_1' => $this->float()->defaultValue(0),

            'old_reseller_2' => $this->float()->defaultValue(0),
            'new_reseller_2' => $this->float()->defaultValue(0),

            'old_reseller_3' => $this->float()->defaultValue(0),
            'new_reseller_3' => $this->float()->defaultValue(0),

            'config' => $this->text(),
            'updated_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        // Group
        $this->createTable('{{%game_setting}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string(255)->notNull(),
            'value' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->createTable('{{%game_method}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'description' => $this->string(255)->notNull(),
            'safe' => $this->integer()->notNull(),
            'speed' => $this->integer()->notNull(),
            'price' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%game_version}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->createTable('{{%game_package}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'group_id' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%game_group}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'method' => $this->string(255)->notNull(),
            'version' => $this->string(255)->notNull(),
            'status' => $this->string(255),
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $alterGroup = "ALTER TABLE {{%game_group}} MODIFY `status` ENUM('Y', 'N', 'D') NOT NULL DEFAULT 'Y'";
            $commandGroup = $this->db->createCommand($alterGroup);
            $commandGroup->execute();
        }
        // Category
        $this->createTable('{{%game_category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'status' => $this->string(1)->defaultValue('Y')
        ], $tableOptions);

        $this->createTable('{{%game_category_item}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
        ], $tableOptions);

        // Subscriber
        $this->createTable('{{%game_subscriber}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%game}}');
        $this->dropTable('{{%game_image}}');
        $this->dropTable('{{%game_price}}');
        $this->dropTable('{{%game_group}}');
        $this->dropTable('{{%game_setting}}');
        $this->dropTable('{{%game_category}}');
        $this->dropTable('{{%game_category_item}}');
        $this->dropTable('{{%game_subscriber}}');
    }
}
