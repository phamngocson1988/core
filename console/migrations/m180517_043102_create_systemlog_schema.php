<?php

use yii\db\Migration;

/**
 * Class m180517_043102_create_systemlog_schema
 */
class m180517_043102_create_systemlog_schema extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180517_043102_create_systemlog_schema cannot be reverted.\n";

        return false;
    }

    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /* Product table */
        $this->createTable('{{%system_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'action' => $this->string(50)->notNull(),
            'description' => $this->string(200),
            'data' => $this->text(),
            'created_at' => $this->dateTime()->notNull(),           
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%system_log}}');
    }
}
