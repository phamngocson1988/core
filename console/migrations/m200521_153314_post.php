<?php

use yii\db\Migration;

/**
 * Class m200521_153314_post
 */
class m200521_153314_post extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255),
            'content' => $this->text(),
            'operator_id' => $this->integer(),
            'category_id' => $this->integer(),
            'image_id' => $this->integer(),
            'language' => $this->string(16)->notNull()->defaultValue('en-US'),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);
        //ALTER TABLE `post` ADD `language` VARCHAR(16) NOT NULL DEFAULT 'en-US';

        $this->createTable('{{%post_category}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer(11)->notNull(),
            'category_id' => $this->integer(11)->notNull(),
        ], $tableOptions);

        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255),
            'image_id' => $this->integer(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%post}}');
        $this->dropTable('{{%post_category}}');
        $this->dropTable('{{%category}}');
    }
}
