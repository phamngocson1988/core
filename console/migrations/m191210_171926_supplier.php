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
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m191210_171926_supplier cannot be reverted.\n";
        $this->dropTable('{{%supplier}}');
        $this->dropTable('{{%supplier_game}}');
        return false;
    }
}
