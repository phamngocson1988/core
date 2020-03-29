<?php

use yii\db\Migration;

/**
 * Class m200328_162324_bank
 */
class m200328_162324_bank extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bank}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(16)->notNull()->unique(),
            'name' => $this->string(128)->notNull(),
            'short_name' => $this->string(128),
            'country' => $this->string(16),
            'currency' => $this->string(16),
            'transfer_cost' => $this->float(),
            'transfer_cost_type' => $this->string(16)->defaultValue('fix'), //fix, percent
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'status' => $this->integer(),
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $alterTransferCostType = "ALTER TABLE {{%bank}} MODIFY `transfer_cost_type` ENUM('fix', 'percent') NOT NULL DEFAULT 'fix'";
            $commandTransferCostType = $this->db->createCommand($alterTransferCostType);
            $commandTransferCostType->execute();
        }

        $this->createTable('{{%bank_account}}', [
            'id' => $this->primaryKey(),
            'bank_id' => $this->integer()->notNull(),
            'account_name' => $this->string(128)->notNull(),
            'account_number' => $this->string(128),
            'branch' => $this->string(128),
            'branch_address' => $this->string(512),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'status' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%bank_account_role}}', [
            'id' => $this->primaryKey(),
            'bank_account_id' => $this->integer()->notNull(),
            'role_id' => $this->string(64)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'updated_by' => $this->integer()->notNull(),
            'status' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%bank}}');
        $this->dropTable('{{%bank_account}}');
        $this->dropTable('{{%bank_account_role}}');
    }
}
