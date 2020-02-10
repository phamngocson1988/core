<?php

use yii\db\Migration;

/**
 * Class m191210_171926_supplier
 */
class m191210_171926_supplier extends Migration
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

        $this->createTable('{{%supplier}}', [
            'user_id' => $this->integer()->notNull(),
            'status' => $this->string(10),
            'created_by' => $this->integer(),  
            'updated_by' => $this->integer(),  
            'created_at' => $this->dateTime(),            
            'updated_at' => $this->dateTime(),            
        ], $tableOptions);
        $this->addPrimaryKey('supplier_pk', '{{%supplier}}', ['user_id']);
        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%supplier}} MODIFY `status` ENUM('disabled', 'enabled') NOT NULL DEFAULT 'enabled'";
            $command = $this->db->createCommand($alter);
            $command->execute();
        }

        $this->createTable('{{%supplier_game}}', [
            'supplier_id' => $this->integer()->notNull(),
            'game_id' => $this->integer()->notNull(),
            'price' => $this->float(),
            'status' => $this->string(10),
            'created_by' => $this->integer(),  
            'updated_by' => $this->integer(),  
            'created_at' => $this->dateTime(),            
            'updated_at' => $this->dateTime(),           
        ], $tableOptions);
        $this->addPrimaryKey('supplier_game_pk', '{{%supplier_game}}', ['supplier_id', 'game_id']);
        if ($this->db->driverName === 'mysql') {
            $alterGame = "ALTER TABLE {{%supplier_game}} MODIFY `status` ENUM('disabled', 'enabled') NOT NULL DEFAULT 'disabled'";
            $command = $this->db->createCommand($alterGame);
            $command->execute();
        }

        $this->createTable('{{%order_supplier}}', [
            'id' => $this->primaryKey(),
            'order_id' => $this->integer()->notNull(),
            'supplier_id' => $this->integer()->notNull(),
            'price' => $this->float(),
            'quantity' => $this->float()->defaultValue(0),
            'total_price' => $this->float()->defaultValue(0),
            'rate_usd' => $this->float(),
            'status' => $this->string(10)->notNull(),
            'created_at' => $this->dateTime(),            
            'updated_at' => $this->dateTime(),    
            'requested_at' => $this->dateTime(),  
            'requested_by' => $this->integer(),         
            'approved_at' => $this->dateTime(),           
            'rejected_at' => $this->dateTime(),           
            'stopped_at' => $this->dateTime(),           
            'retaken_at' => $this->dateTime(),           
            'retaken_by' => $this->integer(),           
        ], $tableOptions);
        if ($this->db->driverName === 'mysql') {
            $orderSupplierStatus = "ALTER TABLE {{%order_supplier}} MODIFY `status` ENUM('request','approve','reject','retake','stop') NOT NULL DEFAULT 'request'";
            $command = $this->db->createCommand($orderSupplierStatus);
            $command->execute();
        }

        $this->createTable('{{%supplier_bank}}', [
            'id' => $this->primaryKey(),
            'supplier_id' => $this->integer()->notNull(),
            'bank_code' => $this->string(50)->notNull(),
            'province' => $this->string(128),
            'city' => $this->string(128),
            'branch' => $this->string(128),
            'account_number' => $this->string(50)->notNull(),
            'account_name' => $this->string(50)->notNull()
        ], $tableOptions);

        $this->createTable('{{%supplier_wallet}}', [
            'id' => $this->primaryKey(),
            'type' => $this->string(1)->notNull(),
            'supplier_id' => $this->integer(11)->notNull(),
            'amount' => $this->float()->notNull()->defaultValue(0),
            'source' => $this->string(100), 
            'key' => $this->string(100), 
            'description' => $this->string(100), 
            'created_by' => $this->integer(11),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(11),
            'status' => $this->string(10)->notNull()->defaultValue('pending'),
        ]);
        if ($this->db->driverName === 'mysql') {
            $walletStatus = "ALTER TABLE {{%supplier_wallet}} MODIFY `status` ENUM('pending','completed') NOT NULL DEFAULT 'pending'";
            $command = $this->db->createCommand($walletStatus);
            $command->execute();

            $walletType = "ALTER TABLE {{%supplier_wallet}} MODIFY `status` ENUM('I','O') NOT NULL";
            $command = $this->db->createCommand($walletType);
            $command->execute();
        }

        $this->createTable('{{%supplier_withdraw_request}}', [
            'id' => $this->primaryKey(),
            'supplier_id' => $this->integer(11)->notNull(),
            'bank_id' => $this->integer(11)->notNull(),
            'bank_code' => $this->string(50)->notNull(),
            'account_number' => $this->string(50)->notNull(),
            'account_name' => $this->string(50)->notNull()
            'amount' => $this->float()->notNull()->defaultValue(0),
            'available_balance' => $this->float()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer(11),
            'approved_at' => $this->dateTime(), 
            'approved_by' => $this->integer(11), 
            'done_at' => $this->dateTime(), 
            'done_by' => $this->integer(11),
            'cancelled_at' => $this->dateTime(), 
            'cancelled_by' => $this->integer(11),
            'note' => $this->dateTime()->notNull(),
            'evidence' => $this->dateTime(),
            'status' => $this->string(10)->notNull()->defaultValue('request'),
        ]);
        if ($this->db->driverName === 'mysql') {
            $requestStatus = "ALTER TABLE {{%supplier_withdraw_request}} MODIFY `status` ENUM('request','approve','done','cancel') NOT NULL DEFAULT 'request'";
            $command = $this->db->createCommand($requestStatus);
            $command->execute();
        }

        $this->createTable('{{%supplier_game_suggestion}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'link' => $this->string(255),
            'description' => $this->text(),
            'image_id' => $this->integer(11),
            'status' => $this->string(10)->notNull()->defaultValue('new'),
            'game_id' => $this->integer(11), 
            'created_at' => $this->dateTime(), 
            'created_by' => $this->integer(11),
            'updated_at' => $this->dateTime(), 
            'updated_by' => $this->integer(11),
        ]);
        if ($this->db->driverName === 'mysql') {
            $suggestStatus = "ALTER TABLE {{%supplier_game_suggestion}} MODIFY `status` ENUM('new','done') NOT NULL DEFAULT 'new'";
            $command = $this->db->createCommand($suggestStatus);
            $command->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191210_171926_supplier cannot be reverted.\n";
        $this->dropTable('{{%supplier}}');
        $this->dropTable('{{%supplier_game}}');
        $this->dropTable('{{%order_supplier}}');
        $this->dropTable('{{%supplier_bank}}');
        $this->dropTable('{{%supplier_wallet}}');
        $this->dropTable('{{%supplier_withdraw_request}}');
        $this->dropTable('{{%supplier_game_suggestion}}');
        return false;
    }
}
