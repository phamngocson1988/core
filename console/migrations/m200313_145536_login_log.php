<?php

use yii\db\Migration;

/**
 * Class m200313_145536_login_log
 */
class m200313_145536_login_log extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%login_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'role' => $this->string(16)->notNull(),
            'ip' => $this->string(16),
            'browser' => $this->string(128),
            'device' => $this->string(128),
            'location' => $this->string(512),
            'created_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%login_log}}');
    }
}
