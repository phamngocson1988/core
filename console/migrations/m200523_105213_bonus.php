<?php

use yii\db\Migration;

/**
 * Class m200523_105213_bonus
 */
class m200523_105213_bonus extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bonus}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255),
            'content' => $this->text(),
            'operator_id' => $this->integer(),
            'image_id' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%bonus}}');
    }
}
