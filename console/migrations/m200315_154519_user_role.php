<?php

use yii\db\Migration;

/**
 * Class m200315_154519_user_role
 */
class m200315_154519_user_role extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user_role}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'role' => $this->string(16)->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user_role}}');
    }
}
