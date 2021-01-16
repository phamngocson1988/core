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

        $this->createTable('{{%payment_info}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(11)->notNull(),
            'payment_id' => $this->string(50),
            'payment_method' => $this->string(50),
            'payment_type' => $this->string(10),
            'payment_data' => $this->text(),
            'amount' => $this->float(),
            'currency' => $this->string(50),
            'amount_usd' => $this->float(),
            'exchange_rate' => $this->float(),
            'status' => $this->smallInteger()->notNull()->defaultValue(9), // 10 for complete, 5 for pending, 1 for deleted 
            'object_ref' => $this->string(50),
            'object_key' => $this->integer(),
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        echo "m200613_083530_affiliate cannot be reverted.\n";

        $this->dropTable('{{%payment_info}}');
        return false;
    }
}
