<?php

use yii\db\Migration;

/**
 * Class m210118_060508_currency
 */
class m210118_060508_currency extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%currency}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(50)->notNull()->unique(),
            'name' => $this->string(50)->notNull(),
            'symbol' => $this->string(50),
            'format' => $this->string(50),
            'exchange_rate' => $this->float()->notNull(),
            'rate_log' => $this->text(),
            'is_fix' => $this->boolean()->defaultValue(false),
            'status' => $this->smallInteger()->notNull()->defaultValue(10), // 10 for active, 5 for disactive 
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        echo "m200613_083530_affiliate cannot be reverted.\n";

        $this->dropTable('{{%currency}}');
        return false;
    }
}
