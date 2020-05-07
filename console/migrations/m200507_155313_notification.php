<?php

use yii\db\Migration;

/**
 * Class m200507_155313_notification
 */
class m200507_155313_notification extends Migration
{
    /**
     * Create table `desktop_notifications`
     */
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // desktop_notifications
        $this->createTable('{{%desktop_notifications}}', [
            'id' => $this->primaryKey(),
            'class' => $this->string(64)->notNull(),
            'key' => $this->string(32)->notNull(),
            'title' => $this->string(255)->notNull(),
            'message' => $this->string(255)->notNull(),
            'route' => $this->string(255)->notNull(),
            'seen' => $this->boolean()->notNull()->defaultValue(false),
            'read' => $this->boolean()->notNull()->defaultValue(false),
            'user_id' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
            'created_at' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);
        $this->createIndex('index_2', '{{%desktop_notifications}}', ['user_id']);
        $this->createIndex('index_3', '{{%desktop_notifications}}', ['created_at']);
        $this->createIndex('index_4', '{{%desktop_notifications}}', ['seen']);

    }

    /**
     * Drop table `desktop_notifications`
     */
    public function down()
    {
        $this->dropTable('{{%desktop_notifications}}');
    }
}
