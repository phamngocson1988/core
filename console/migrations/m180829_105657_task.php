<?php

use yii\db\Migration;

/**
 * Class m180829_105657_task
 */
class m180829_105657_task extends Migration
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
        echo "m180829_105657_task cannot be reverted.\n";

        return false;
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /* Product table */
        $this->createTable('{{%task}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'descripition' => $this->text(),
            'created_by' => $this->integer()->notNull(),  
            'created_at' => $this->integer()->notNull(),            
            'updated_at' => $this->integer()->notNull(),            
            'start_date' => $this->integer()->notNull(),            
            'due_date' => $this->integer()->notNull(),            
            'assignee' => $this->integer()->notNull(),            
            'percent' => $this->integer()->notNull(),            
            'status' => $this->string()->comment('Enum: new,inprogress,done,invalid')->defaultValue('new')->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%task}}');
    }
}
