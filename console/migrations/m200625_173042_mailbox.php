<?php

use yii\db\Migration;

/**
 * Class m200625_173042_mailbox
 */
class m200625_173042_mailbox extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%mail_thread}}', [
            'id' => $this->primaryKey(),
            'subject' => $this->string(255)->notNull(),
            'from' => $this->integer()->notNull(),
            'to' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%mail}}', [
            'id' => $this->primaryKey(),
            'mail_thread_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'read' => $this->boolean()->defaultValue(false),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'read_at' => $this->dateTime(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%mail_thread}}');
        $this->dropTable('{{%mail}}');
    }
}
