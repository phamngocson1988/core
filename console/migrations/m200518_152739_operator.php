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
            'backup_url' => $this->string(1024),
            'withdrawal_limit' => $this->integer(),
            'withdrawal_currency' => $this->string(15),
            'rebate' => $this->integer(),
            'owner' => $this->string(255),
            'established' => $this->integer(),
            'livechat_support' => $this->boolean()->defaultValue(false),
            'support_email' => $this->string(255),
            'support_phone' => $this->string(255),
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
            'star' => $this->integer()->defaultValue(1),
            'notify_register' => $this->boolean()->notNull()->defaultValue(false),
            'experience' => $this->boolean()->notNull()->defaultValue(false),
            'reply' => $this->text(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'replied_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'replied_at' => $this->dateTime(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%operator}}');
        $this->dropTable('{{%operator_favorite}}');
        $this->dropTable('{{%operator_review}}');
    }
}
