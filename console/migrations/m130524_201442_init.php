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
            'name' => $this->string()->notNull(),
            'username' => $this->string()->notNull()->unique(),
            'avatar' => $this->integer(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'email' => $this->string()->notNull()->unique(),
            'country_code' => $this->string(10),
            'subscription' => $this->integer()->defaultValue(0),
            'phone' => $this->string(50),
            'is_verify_phone' => $this->smallInteger()->defaultValue(0),
            'address' => $this->string(200),
            'birthday' => $this->date(),
            'favorite' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'refer_code' => $this->string(10),
            'referred_by' => $this->integer(),
            'affiliated_with' => $this->integer(),
            'is_reseller' => $this->smallInteger()->defaultValue(1),
            'reseller_level' => $this->smallInteger()->defaultValue(1),
            'saler_id' => $this->integer(),
            'saler_code' => $this->string(50),
            'is_supplier' => $this->smallInteger()->defaultValue(1),
            'marketing_id' => $this->integer(),
            'trust' => $this->string(1),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);
        if ($this->db->driverName === 'mysql') {
            $alterUserTrust = "ALTER TABLE {{%user}} MODIFY `trust` ENUM('Y', 'N') NOT NULL DEFAULT 'N'";
            $command = $this->db->createCommand($alterUserTrust);
            $command->execute();
        }

        /* Image table */
        $this->createTable('{{%image}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'extension' => $this->string(10)->notNull(),
            'size' => $this->string(20)->notNull(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%file}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'extension' => $this->string(10)->notNull(),
            'size' => $this->string(20)->notNull(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%image}}');
        $this->dropTable('{{%file}}');
    }
}
