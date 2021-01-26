<?php

use yii\db\Migration;

/**
 * Class m210109_092832_payment_info
 */
class m210109_092832_payment_info extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%payment}}', [
            'id' => $this->primaryKey(),
            'payer' => $this->string(255),
            'payment_id' => $this->string(255),
            'paygate' => $this->string(255)->notNull(),
            'payment_type' => $this->string(10), // online, offline
            'payment_data' => $this->text(),
            'payment_time' => $this->dateTime(),
            'payment_note' => $this->string(255), // it could be a comment from customer
            'amount' => $this->float(),
            'currency' => $this->string(50),
            'kingcoin' => $this->float(),
            'exchange_rate' => $this->float(),
            'note' => $this->string(255), // this field saves staff comment, when he proceed this payment information
            'file_id' => $this->integer(),
            'user_id' => $this->integer(), // ref to User table: this payment used for which user
            'status' => $this->smallInteger()->notNull()->defaultValue(5), // 10 for complete, 5 for pending, 1 for deleted 
            'object_name' => $this->string(255),
            'object_key' => $this->integer(),
            'confirmed_by' => $this->integer(),
            'confirmed_at' => $this->dateTime(),
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        echo "m200613_083530_affiliate cannot be reverted.\n";

        $this->dropTable('{{%payment}}');
        return false;
    }
}
