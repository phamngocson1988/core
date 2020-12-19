<?php

use yii\db\Migration;

/**
 * Class m200711_164917_forum
 */
class m200711_164917_forum extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%forum_topic}}', [
            'id' => $this->primaryKey(),
            'subject' => $this->string(255)->notNull(),
            'language' => $this->string(16)->notNull()->defaultValue('en-US'),
            'slug' => $this->string(255),
            'category_id' => $this->integer(),
            'status' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'created_by' => $this->integer()->notNull(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%forum_post}}', [
            'id' => $this->primaryKey(),
            'topic_id' => $this->integer()->notNull(),
            'content' => $this->text()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'is_approved' => $this->boolean()->defaultValue(true),
            'status' => $this->integer()->defaultValue(10),,
        ], $tableOptions);

        $this->createTable('{{%forum_category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255),
            'intro' => $this->text(),
            'language' => $this->string(16)->notNull()->defaultValue('en-US'),
            'position' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%forum_section}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255),
            'intro' => $this->text(),
            'position' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%forum_section_category}}', [
            'id' => $this->primaryKey(),
            'section_id' => $this->integer()->notNull(),
            'category_id' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%forum_like}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->dateTime(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%forum_topic}}');
        $this->dropTable('{{%forum_post}}');
        $this->dropTable('{{%forum_like}}');
        $this->dropTable('{{%forum_category}}');
        $this->dropTable('{{%forum_section}}');
        $this->dropTable('{{%forum_section_category}}');
    }
}
