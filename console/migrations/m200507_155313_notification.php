<?php

use yii\db\Migration;

/**
 * Class m200507_155313_notification
 */
class m200507_155313_notification extends Migration
{
    /**
     * Create table `device_notifications`
     */
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // device_notifications
        $this->createTable('{{%device_notifications}}', [
            'id' => $this->primaryKey(),
            'class' => $this->string(64)->notNull(),
            'key' => $this->string(32)->notNull(),
            'title' => $this->string(255)->notNull(),
            'icon' => $this->string(255),
            'message' => $this->string(255)->notNull(),
            'device' => $this->string(64)->notNull(),
            'route' => $this->string(255)->notNull(),
            'seen' => $this->boolean()->notNull()->defaultValue(false),
            'read' => $this->boolean()->notNull()->defaultValue(false),
            'user_id' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
            'created_at' => $this->integer(11)->unsigned()->notNull()->defaultValue(0),
        ], $tableOptions);
        $this->createIndex('index_2', '{{%device_notifications}}', ['user_id']);
        $this->createIndex('index_3', '{{%device_notifications}}', ['created_at']);
        $this->createIndex('index_4', '{{%device_notifications}}', ['seen']);
        $this->createIndex('index_5', '{{%device_notifications}}', ['device']);

    }

    /**
     * Drop table `device_notifications`
     */
    public function down()
    {
        $this->dropTable('{{%device_notifications}}');
    }
}
