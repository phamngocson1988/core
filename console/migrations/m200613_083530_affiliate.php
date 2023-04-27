<?php

use yii\db\Migration;

/**
 * Class m200613_083530_affiliate
 */
class m200613_083530_affiliate extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%affiliate}}', [
            'user_id' => $this->integer()->notNull(),
            'preferred_im' => $this->string(50)->notNull(),
            'im_account' => $this->string(255)->notNull(),
            'company' => $this->string()->notNull(),
            'channel' => $this->string(),
            'channel_type' => $this->string(50)->notNull(), // set 1: pending, 2: completed
            'status' => $this->integer()->defaultValue(1),
            'code' => $this->string(50),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'approved_at' => $this->dateTime(),
            'approved_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%affiliate_account}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'payment_method' => $this->string(255)->notNull(),
            'account_number' => $this->string(255)->notNull(),
            'account_name' => $this->string(255)->notNull(),
            'region' => $this->string(255)->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%affiliate_commission}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'commission' => $this->float()->notNull()->defaultValue(0),
            'order_id' => $this->integer()->notNull(),
            'member_id' => $this->integer()->notNull(),
            'description' => $this->string(255),
            'created_at' => $this->date(),
            'valid_from_date' => $this->date(),
            'valid_to_date' => $this->date(),
            'status' => $this->integer()->notNull()->defaultValue(1), // set 1: valid, 2: withdrawed
        ], $tableOptions);

        $this->createTable('{{%affiliate_commission_withdraw}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->float()->notNull()->defaultValue(0),
            'affiliate_account' => $this->integer()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'approved_at' => $this->dateTime(),
            'approved_by' => $this->integer(),
            'executed_at' => $this->dateTime(),
            'executed_by' => $this->integer(),
            'note' => $this->string(255),
            'evidence' => $this->integer(),
            'status' => $this->integer()->notNull()->defaultValue(1), // set 1: request, 2: approved, 3: executed
        ], $tableOptions);
    }

    public function down()
    {
        echo "m200613_083530_affiliate cannot be reverted.\n";

        $this->dropTable('{{%affiliate}}');
        $this->dropTable('{{%affiliate_account}}');
        $this->dropTable('{{%affiliate_commission}}');
        $this->dropTable('{{%affiliate_commission_withdraw}}');
        return false;
    }
}
