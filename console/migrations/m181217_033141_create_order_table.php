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
            'payment_type' => $this->string(10),
            'payment_data' => $this->text(),
            'payment_content' => $this->text(),
            'payment_token' => $this->text(),
            'rate_usd' => $this->float()->defaultValue(22000),
            'price' => $this->float()->defaultValue(0),
            'flash_sale' => $this->boolean()->defaultValue(false),
            'cogs_price' => $this->float(1)->defaultValue(0),
            'original_quantity' => $this->float(1),
            'quantity' => $this->float(1)->notNull()->defaultValue(1),
            'sub_total_price' => $this->float()->defaultValue(0),
            'total_discount' => $this->float()->defaultValue(0),
            'total_fee' => $this->float()->defaultValue(0),
            'total_tax' => $this->float()->defaultValue(0),
            'total_price' => $this->float(1)->defaultValue(0),
            'total_cogs_price' => $this->float(1)->defaultValue(0),
            'total_price_by_currency' => $this->float(1)->defaultValue(0),
            'currency' => $this->string(10)->defaultValue('USD'),
            'rate_currency' => $this->float(),
            'customer_id' => $this->integer(11)->notNull(),
            'customer_name' => $this->string(255),
            'customer_email' => $this->string(255),
            'customer_phone' => $this->string(20),
            'user_ip' => $this->string(50),
            'saler_id' => $this->integer(11),
            'orderteam_id' => $this->integer(11),
            'rating' => $this->integer(2)->defaultValue(0),
            'comment_rating' => $this->string(100),
            'created_at' => $this->dateTime(),
            'pending_at' => $this->dateTime(),
            'processing_at' => $this->dateTime(),
            'distributed_at' => $this->dateTime(),
            'approved_at' => $this->dateTime(),
            'completed_at' => $this->dateTime(),
            'confirmed_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'payment_at' => $this->dateTime(),
            'status' => $this->integer(11)->notNull(),
            'state' => $this->integer(11),
            'request_cancel' => $this->integer(4)->defaultValue(0),
            'request_cancel_time' => $this->dateTime(),
            'request_cancel_description' => $this->string(512),

            // Game infor
            'game_id' => $this->integer(11)->notNull(),
            'game_title' => $this->string(255)->notNull(),
            'unit_name' => $this->string(50)->notNull(),
            'sub_total_unit' => $this->integer(11)->defaultValue(0),
            'promotion_unit' => $this->integer(11)->defaultValue(0),
            'promotion_id' => $this->integer(11),
            'promotion_code' => $this->string(50),
            'total_unit' => $this->integer(11)->defaultValue(0),
            'doing_unit' => $this->float()->defaultValue(0),
            'username' => $this->string(255),
            'password' => $this->string(255),
            'platform' => $this->string(20),
            'login_method' => $this->string(20),
            'character_name' => $this->string(255),
            'recover_code' => $this->string(255),
            'recover_file_id' => $this->string(255),
            'server' => $this->string(255),
            'note' => $this->string(255),
            'raw' => $this->text(),
            'bulk' => $this->integer(11),
            'evidence' => $this->string(255),
            'process_start_time' => $this->dateTime(),
            'process_end_time' => $this->dateTime(),
            'process_duration_time' => $this->integer(11),
            'supplier_id' => $this->integer(11),
            'order_from_sublink' => $this->boolean()->defaultValue(false),
            'reseller_id' => $this->integer(11),

            // commission
            'expected_profit' => $this->integer(), //vnd
            'real_profit' => $this->integer(),
            'am_commission_rate' => $this->float(), // profit for kinggems
            'ot_commission_rate' => $this->float(), // profit for kinggems
            'saler_order_commission' => $this->integer(), // refer to saler_id
            'orderteam_order_commission' => $this->integer(), // refer to orderteam_id
            'saler_sellout_commission' => $this->integer(), // refer to saler_id
            'orderteam_sellout_commission' => $this->integer(), // refer to orderteam_id
        ]);//16868688

        if ($this->db->driverName === 'mysql') {
            $status = "ALTER TABLE {{%order}} MODIFY `status` ENUM('verifying','pending','processing','partial','completed','deleted') NOT NULL DEFAULT 'verifying'";
            $command = $this->db->createCommand($status);
            $command->execute();

            $state = "ALTER TABLE {{%order}} MODIFY `state` ENUM('pending_information', 'pending_confirmation')";
            $commandState = $this->db->createCommand($state);
            $commandState->execute();

            $statusIndex = "CREATE INDEX index_status ON {{%order}} (`status`)";
            $this->db->createCommand($statusIndex)->execute();
            $gameIndex = "CREATE INDEX index_game ON {{%order}} (`game_id`)";
            $this->db->createCommand($gameIndex)->execute();
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

        $this->createTable('{{%order_log}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11),
            'description' => $this->string(255)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ]);

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
            'content_type' => $this->string(16)->defaultValue('text'),
            'is_read' => $this->integer(1)->defaultValue(0),
            'is_customer' => $this->string(1)->defaultValue('N'),
            'object_name' => $this->string(10),
            'ouath_sublink_client_id' => $this->string(255),
            'user_sublink_id' => $this->string(255),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer(11)->notNull(),
        ]);
        if ($this->db->driverName === 'mysql') {
            $isCustomer = "ALTER TABLE {{%order_complains}} MODIFY `is_customer` ENUM('Y','N') DEFAULT 'N'";
            $command = $this->db->createCommand($isCustomer);
            $command->execute();
            $sysName = "ALTER TABLE {{%order_complains}} MODIFY `object_name` ENUM('customer','admin', 'supplier')";
            $sysCommand = $this->db->createCommand($sysName);
            $sysCommand->execute();
        }

        $this->createTable('{{%order_reseller}}', [
            'order_id' => $this->integer(11)->notNull(),
            'raw_price' => $this->float()->notNull(),
            'customer_email' => $this->string(255)->notNull(),
            'status' => $this->integer(11)->notNull()->defaultValue(1),
        ]);

        $this->createTable('{{%order_commission}}', [
            'order_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'game_id' => $this->integer(11)->notNull(),
            'quantity' => $this->float(1)->notNull(),
            'commission_type' => $this->string()->notNull(),
            'role' => $this->string()->notNull(),
            'username' => $this->string(),
            'user_commission' => $this->integer(),
            'description' => $this->text(),
            'created_at' => $this->dateTime()->notNull(),
        ]);
        $this->addPrimaryKey('order_commission_pk', '{{%order_commission}}', ['order_id', 'user_id', 'commission_type', 'role']);
        if ($this->db->driverName === 'mysql') {
            $commissionTypeEnum = "ALTER TABLE {{%order_commission}} MODIFY `commission_type` ENUM('order', 'sellout')";
            $command = $this->db->createCommand($commissionTypeEnum);
            $command->execute();

            $commissionRoleEnum = "ALTER TABLE {{%order_commission}} MODIFY `role` ENUM('saler', 'orderteam')";
            $command = $this->db->createCommand($commissionRoleEnum);
            $command->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order}}');
        $this->dropTable('{{%order_image}}');
        $this->dropTable('{{%order_comments}}');
        $this->dropTable('{{%order_file}}');
        $this->dropTable('{{%order_log}}');
    }
}
