<?php

use yii\db\Migration;

/**
 * Class m190812_073943_question
 */
class m190812_073943_question extends Migration
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

        $this->createTable('{{%question}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'content' => $this->text()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),  
            'updated_by' => $this->integer(),  
            'created_at' => $this->dateTime()->notNull(),            
            'updated_at' => $this->dateTime(),            
        ], $tableOptions);

        $this->createTable('{{%question_category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'link' => $this->string(512),
            'hot' => $this->smallInteger()->defaultValue(0),// 1||0
            'position' => $this->integer()->defaultValue(0),
        ], $tableOptions);

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190812_073943_question cannot be reverted.\n";
        $this->dropTable('{{%question}}');
        $this->dropTable('{{%question_category}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190812_073943_question cannot be reverted.\n";

        return false;
    }
    */
}
