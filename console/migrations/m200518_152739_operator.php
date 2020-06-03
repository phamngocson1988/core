<?php

use yii\db\Migration;

/**
 * Class m200518_152739_operator
 */
class m200518_152739_operator extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%operator}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'slug' => $this->string(255),
            'main_url' => $this->string(255),
            'logo' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%operator_favorite}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'operator_id' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%operator_review}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'operator_id' => $this->integer()->notNull(),
            'good_thing' => $this->text(),
            'bad_thing' => $this->text(),
            'star' => $this->integer()->defaultValue(0),
            'notify_register' => $this->boolean()->notNull()->defaultValue(false),
            'experience' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

    }

    public function down()
    {
        $this->dropTable('{{%operator}}');
        $this->dropTable('{{%operator_favorite}}');
    }
}
