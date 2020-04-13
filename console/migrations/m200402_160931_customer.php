<?php

use yii\db\Migration;

/**
 * Class m200402_160931_customer
 */
class m200402_160931_customer extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%customer}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'phone' => $this->string(16)->notNull(),
            'short_name' => $this->string(255),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%customer}}');
    }
}
