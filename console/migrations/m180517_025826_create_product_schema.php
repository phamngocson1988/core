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
            'price' => $this->integer()->notNull(),
            'reseller_price' => $this->integer(),
            'original_price' => $this->integer(),
            'pack' => $this->integer()->notNull()->defaultValue(1),
            'meta_title' => $this->string(160),
            'meta_keyword' => $this->string(160),
            'meta_description' => $this->string(160),
            'status' => $this->string()->comment('Enum: Y,N,D')->defaultValue('Y')->notNull(),
            'pin' => $this->integer()->defaultValue(0),
            'created_at' => $this->dateTime(),            
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->dateTime(),
            'deleted_by' => $this->integer(),
            'price1' => $this->float(),
            'price2' => $this->float(),
            'price3' => $this->float(),
            'average_speed' => $this->integer(), // seconds
            'number_supplier' => $this->integer(),
            'remark' => $this->text(),
            'price_remark' => $this->text(),
            'google_ads' => $this->text(),
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%game}} MODIFY `status` ENUM('Y', 'N', 'D') NOT NULL DEFAULT 'Y'";
            $command = $this->db->createCommand($alter);
            $command->execute();
        }

        $this->createTable('{{%game_image}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'image_id' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createTable('{{%game_price}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'provider_id' => $this->integer(),
            'price' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%game_price_log}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'old_price' => $this->float()->notNull(),
            'new_price' => $this->float()->notNull(),

            'old_reseller_1' => $this->float()->notNull(),
            'new_reseller_1' => $this->float()->notNull(),

            'old_reseller_2' => $this->float()->notNull(),
            'new_reseller_2' => $this->float()->notNull(),

            'old_reseller_3' => $this->float()->notNull(),
            'new_reseller_3' => $this->float()->notNull(),

            'config' => $this->text(),
            'updated_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%game}}');
        $this->dropTable('{{%game_image}}');
        $this->dropTable('{{%game_price}}');
    }
}
