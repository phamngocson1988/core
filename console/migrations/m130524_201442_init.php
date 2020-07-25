<?php

use yii\db\Migration;

class m130524_201442_init extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(255)->notNull()->unique(),
            'firstname' => $this->string(255),
            'lastname' => $this->string(255),
            'country' => $this->string(64),
            'gender' => $this->string(1),
            'avatar' => $this->integer(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'password_reset_token' => $this->string(255)->unique(),
            'email' => $this->string(255)->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'operator_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'last_login' => $this->dateTime(),
        ], $tableOptions);

        /* Image table */
        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'extension' => $this->string(10)->notNull(),
            'size' => $this->string(20)->notNull(),
            'operator_id' => $this->integer(),
            'category' => $this->string(100),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'extension' => $this->string(10)->notNull(),
            'size' => $this->string(20)->notNull(),
            'operator_id' => $this->integer(),
            'category' => $this->string(100),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%user_setting}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'key' => $this->string(255)->notNull(),
            'value' => $this->text(),
        ], $tableOptions);

        $this->createTable('{{%user_log}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'object' => $this->string(255), // which object
            'action' => $this->string(255), // which action
            'description' => $this->string(255), // detail
            'created_at' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%user_point}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'point' => $this->integer()->notNull()->defaultValue(0),
            'description' => $this->string(255),
            'created_at' => $this->integer(),
        ], $tableOptions);
         
        $this->createTable('{{%user_badge}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'badge' => $this->string(50)->notNull(), //profile,complaint,review
            'key' => $this->integer(), // which action
            'description' => $this->string(255), // detail
            'created_at' => $this->integer(),
        ], $tableOptions);
        if ($this->db->driverName === 'mysql') {
            $alterBadge = "ALTER TABLE {{%user_badge}} MODIFY `badge` ENUM('profile', 'complain', 'review') NOT NULL";
            $command = $this->db->createCommand($alterBadge);
            $command->execute();
        }
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%user_setting}}');
        $this->dropTable('{{%user_log}}');
        $this->dropTable('{{%user_badge}}');
        $this->dropTable('{{%user_point}}');
        $this->dropTable('{{%image}}');
        $this->dropTable('{{%file}}');
    }
}
