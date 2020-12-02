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
            'overview' => $this->text(),
            'main_url' => $this->string(255),
            'backup_url' => $this->string(1024),
            'withdrawal_limit' => $this->integer(),
            'withdrawal_currency' => $this->string(15),
            'withdrawal_time' => $this->string(255),
            'withdrawal_method' => $this->string(255),
            'product' => $this->string(255),
            'deposit_method' => $this->string(255),
            'rebate' => $this->integer(),
            'owner' => $this->string(255),
            'established' => $this->integer(),
            'livechat_support' => $this->boolean()->defaultValue(false),
            'support_email' => $this->string(255),
            'support_phone' => $this->string(255),
            'support_language' => $this->string(255),
            'support_currency' => $this->string(255),
            'license' => $this->string(255),
            'logo' => $this->integer(),
            'language' => $this->string(16)->notNull()->defaultValue('en-US'),
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

        $this->createTable('{{%operator_meta}}', [
            'id' => $this->primaryKey(),
            'key' => $this->string(255)->notNull(),
            'value' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->createTable('{{%operator_staff}}', [
            'id' => $this->primaryKey(),
            'operator_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'role' => $this->smallInteger()->notNull(), 
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%operator}}');
        $this->dropTable('{{%operator_favorite}}');
        $this->dropTable('{{%operator_review}}');
        $this->dropTable('{{%operator_meta}}');
        $this->dropTable('{{%operator_staff}}');
    }
}
