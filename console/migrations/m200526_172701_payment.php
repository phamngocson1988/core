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

        // payment
        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'payment_type' => $this->string(15)->notNull(),
            'content' => $this->text(),
            'logo' => $this->integer(11),
            'config' => $this->text(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_by' => $this->integer(),  
            'updated_by' => $this->integer(),  
            'created_at' => $this->dateTime(),            
            'updated_at' => $this->dateTime(),       
        ], $tableOptions);

    }

    /**
     * Drop table `payment`
     */
    public function down()
    {
        $this->dropTable('{{%payment}}');
    }
}
