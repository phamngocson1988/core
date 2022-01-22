<?php

use yii\db\Migration;

/**
 * Class m220108_115648_job_handler
 */
class m220108_115648_job_handler extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%job_handler}}', [
            'id' => $this->primaryKey(),
            'identifier' => $this->string(50)->notNull(), // identify the object is listening the job
            'object_id' => $this->string(10)->notNull(),
            'job_id' => $this->integer(11)->notNull(),
            'event' => $this->string(50),
            'created_at' => $this->dateTime()
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m220108_115648_job_handler cannot be reverted.\n";
        $this->dropTable('{{%job_handler}}');

        return false;
    }
}
