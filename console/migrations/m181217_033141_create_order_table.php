<?php

use yii\db\Migration;

/**
 * Handles the creation of table `order`.
 */
class m181217_033141_create_order_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%order}}', [
            'id' => $this->primaryKey(),
            'auth_key' => $this->string(10), // used for non-login user
            'payment_id' => $this->string(50),
            'payment_method' => $this->string(50),
            'payment_data' => $this->text(),
            'sub_total_price' => $this->integer(11)->defaultValue(0),
            'total_discount' => $this->integer(11)->defaultValue(0),
            'total_fee' => $this->integer(11)->defaultValue(0),
            'total_tax' => $this->integer(11)->defaultValue(0),
            'total_price' => $this->integer(11)->defaultValue(0),
            'customer_id' => $this->integer(11)->notNull(),
            'customer_name' => $this->string(255),
            'customer_email' => $this->string(255),
            'customer_phone' => $this->string(20),
            'saler_id' => $this->integer(11),
            'orderteam_id' => $this->integer(11),
            'rating' => $this->integer(2)->defaultValue(0),
            'comment_rating' => $this->string(100),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'payment_at' => $this->dateTime(),
            'status' => $this->integer(11),
            'request_cancel' => $this->integer(4)->defaultValue(0),
            'request_cancel_time' => $this->dateTime(),

            // Game infor
            'game_id' => $this->integer(11)->notNull(),
            'game_title' => $this->string(255)->notNull(),
            'game_pack' => $this->float(1)->notNull()->defaultValue(1),
            'unit_name' => $this->string(50)->notNull(),
            'sub_total_unit' => $this->integer(11)->defaultValue(0),
            'promotion_unit' => $this->integer(11)->defaultValue(0),
            'total_unit' => $this->integer(11)->defaultValue(0),
            'doing_unit' => $this->integer(11)->defaultValue(0),
            'username' => $this->string(255),
            'password' => $this->string(255),
            'platform' => $this->string(20),
            'login_method' => $this->string(20),
            'character_name' => $this->string(255),
            'recover_code' => $this->string(255),
            'server' => $this->string(255),
            'note' => $this->string(255),

            // time process
            'process_start_time' => $this->dateTime(),
            'process_end_time' => $this->dateTime(),
            'process_duration_time' => $this->integer(11),
            'provider_id' => $this->integer(11)
        ]);//16868688

        if ($this->db->driverName === 'mysql') {
            $status = "ALTER TABLE {{%order}} MODIFY `status` ENUM('verifying','pending','processing','completed','deleted') NOT NULL DEFAULT 'verifying'";
            $command = $this->db->createCommand($status);
            $command->execute();
        }

        $this->createTable('{{%order_image}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11)->notNull(),
            'image_before_payment' => $this->integer(11),
            'image_after_payment' => $this->integer(11),
        ]);

        $this->createTable('{{%order_file}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11)->notNull(),
            'file_id' => $this->integer(11)->notNull(),
            'file_type' => $this->string(50)->notNull(),
        ]);
        // $this->createTable('{{%order_items}}', [
        //     'id' => $this->primaryKey(),
        //     'item_title' => $this->string(255)->notNull(),
        //     'order_id' => $this->integer(11)->notNull(),
        //     'type' => $this->integer(11)->notNull(),
        //     'product_id' => $this->integer(11),
        //     'game_id' => $this->integer(11),
        //     'price' => $this->integer(11)->notNull(),
        //     'quantity' => $this->integer(11)->notNull(),
        //     'total' => $this->integer(11)->notNull(),
        //     'unit_name' => $this->string(50)->notNull(),
        //     'unit' => $this->integer(11),
        //     'total_unit' => $this->integer(11)->defaultValue(0),
        //     'doing_unit' => $this->integer(11)->defaultValue(0),
        //     'username' => $this->string(255),
        //     'password' => $this->string(255),
        //     'platform' => $this->string(20),
        //     'login_method' => $this->string(20),
        //     'character_name' => $this->string(255),
        //     'recover_code' => $this->string(255),
        //     'server' => $this->string(255),
        //     'note' => $this->string(255),
        //     'image_before_payment' => $this->integer(11),
        //     'image_after_payment' => $this->integer(11),
        // ]);

        if ($this->db->driverName === 'mysql') {
            $type = "ALTER TABLE {{%order_items}} MODIFY `type` ENUM('product','payment_fee') NOT NULL DEFAULT 'product'";
            $command = $this->db->createCommand($type);
            $command->execute();
        }

        $this->createTable('{{%order_comments}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11)->notNull(),
            'comment' => $this->string(255)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer(11)->notNull(),
        ]);

        $this->createTable('{{%order_complain_template}}', [
            'id' => $this->primaryKey(),
            'content' => $this->string(500)->notNull(),
        ]);

        $this->createTable('{{%order_complains}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11)->notNull(),
            'content' => $this->string(500)->notNull(),
            'is_read' => $this->integer(1)->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer(11)->notNull(),
        ]);

        // $this->createTable('{{%order_fee}}', [
        //     'id' => $this->primaryKey(),
        //     'order_id' => $this->integer(11)->notNull(),
        //     'type' => $this->string(50)->notNull(),
        //     'description' => $this->string(100),
        //     'reference' => $this->string(50),
        //     'amount' => $this->integer(11)->notNull()->defaultValue(0),
        // ]);

        // if ($this->db->driverName === 'mysql') {
        //     $type = "ALTER TABLE {{%order_fee}} MODIFY `type` ENUM('discount','fee','tax') NOT NULL";
        //     $command = $this->db->createCommand($type);
        //     $command->execute();
        // }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order}}');
        // $this->dropTable('{{%order_items}}');
        $this->dropTable('{{%order_image}}');
        $this->dropTable('{{%order_comments}}');
        // $this->dropTable('{{%order_fee}}');
        $this->dropTable('{{%order_file}}');
    }
}
