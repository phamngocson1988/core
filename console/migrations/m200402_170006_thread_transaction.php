<?php

use yii\db\Migration;

/**
 * Class m200402_170006_thread_transaction
 */
class m200402_170006_thread_transaction extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%thread_transaction}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(1)->notNull(), // in/out
            'transaction_type' => $this->string(16)->notNull(), // bank/cash
            'bank_id' => $this->integer(11)->notNull(), 
            'country' => $this->string(16)->notNull(), 
            'currency' => $this->string(16)->notNull(), 
            'amount' => $this->float()->defaultValue(0),
            'bank_account_id' => $this->integer(11),
            'description' => $this->string(256), 
            'created_by' => $this->integer(11),
            'created_at' => $this->dateTime()->notNull(),
            'updated_by' => $this->integer(11),
            'updated_at' => $this->dateTime(),
            'completed_by' => $this->integer(),
            'completed_at' => $this->dateTime(),
            'status' => $this->string(16)->notNull()->defaultValue('pending'),
        ]);
        if ($this->db->driverName === 'mysql') {
            $walletStatus = "ALTER TABLE {{%thread_transaction}} MODIFY `status` ENUM('pending','completed') NOT NULL DEFAULT 'pending'";
            $command = $this->db->createCommand($walletStatus);
            $command->execute();

            $walletType = "ALTER TABLE {{%thread_transaction}} MODIFY `type` ENUM('I','O') NOT NULL";
            $command = $this->db->createCommand($walletType);
            $command->execute();

            $walletType = "ALTER TABLE {{%thread_transaction}} MODIFY `transaction_type` ENUM('bank','cash') NOT NULL";
            $command = $this->db->createCommand($walletType);
            $command->execute();
        }

    }

    public function down()
    {
        $this->dropTable('{{%thread_transaction}}');
    }
}
