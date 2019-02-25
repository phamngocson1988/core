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
            'order_status' => $this->string(5)->notNull(),
            'auth_code' => $this->string(50), // used for non-login user
            'payment_status' => $this->string(5)->notNull(),
            'total_payment' => $this->integer(11)->notNull()->defaultValue(0),
            'pre_paid' => $this->integer(11)->defaultValue(0),
            'total_paid' => $this->integer(11)->defaultValue(0),
            'customer_id' => $this->integer(11)->notNull(),
            'customer_name' => $this->string(100)->notNull(),
            'option_id' => $this->integer(11)->notNull(),
            'customer_email' => $this->string(100)->notNull(),
            'created_by' => $this->integer(11),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(11),
            'updated_at' => $this->dateTime(),
            'payment_at' => $this->dateTime(),
            'managed_by' => $this->integer(11),
        ]);

        if ($this->db->driverName === 'mysql') {
            $order_status = "ALTER TABLE {{%order}} MODIFY `order_status` ENUM('draff', 'new', 'inprogress', 'finish') NOT NULL DEFAULT 'new'";
            $command = $this->db->createCommand($order_status);
            $command->execute();

            $payment_status = "ALTER TABLE {{%order}} MODIFY `payment_status` ENUM('none', 'part', 'finish') NOT NULL DEFAULT 'none'";
            $command = $this->db->createCommand($payment_status);
            $command->execute();
        }


        $this->createTable('{{%order_items}}', [
            'id' => $this->primaryKey(),
            'item_title' => $this->string(100)->notNull(),
            'order_id' => $this->integer(11)->notNull(),
            'product_id' => $this->integer(11)->notNull(),
            'option_id' => $this->integer(11)->notNull(),
            'price' => $this->integer(11)->notNull(),
            'quantity' => $this->integer(11)->notNull(),
            'total' => $this->integer(11)->notNull(),
            'gems' => $this->integer(11)->notNull(),
            'accout_username' => $this->string(100)->notNull(),
            'accout_password' => $this->string(100)->notNull(),
            'accout_note' => $this->string(100),
            'image_before_payment' => $this->integer(11),
            'image_after_payment' => $this->integer(11),
        ]);

        $this->createTable('{{%order_comments}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer(11)->notNull(),
            'comment' => $this->string(255)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer(11)->notNull(),
        ]);

        // same idea as customer address
        $this->createTable('{{%customer_game_account}}', [
            'id' => $this->primaryKey(),
            'customer_id' => $this->integer(11)->notNull(),
            'username' => $this->string(255)->notNull(),
            'password' => $this->string(255)->notNull(),
            'character' => $this->string(255)->notNull(),
            'recover_code' => $this->string(255),
            'server' => $this->string(255),
            'note' => $this->string(255),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%order}}');
        $this->dropTable('{{%order_items}}');
        $this->dropTable('{{%order_comments}}');
        $this->dropTable('{{%customer_game_account}}');
    }
}
