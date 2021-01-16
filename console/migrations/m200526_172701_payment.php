<?php

use yii\db\Migration;

/**
 * Class m200526_172701_payment
 */
class m200526_172701_payment extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // paygate
        $this->createTable('{{%paygate}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull(),
            'identifier' => $this->string(50)->notNull(),
            'paygate_type' => $this->string(15)->notNull(),
            'content' => $this->text(),
            'logo' => $this->integer(11),
            'transfer_fee' => $this->float(),
            'transfer_fee_type' => $this->string(15), // percent || fix
            'currency' => $this->string(15),
            'config' => $this->text(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_by' => $this->integer(),  
            'updated_by' => $this->integer(),  
            'created_at' => $this->dateTime(),            
            'updated_at' => $this->dateTime(),     
            'bank_account' => $this->string(128),  
            'approved_by' => $this->integer(),  
            'approved_at' => $this->dateTime(),       
        ], $tableOptions);

    }

    /**
     * Drop table `paygate`
     */
    public function down()
    {
        $this->dropTable('{{%paygate}}');
    }
}
