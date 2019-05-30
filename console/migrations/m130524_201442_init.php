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
            'phone' => $this->string(50),
            'address' => $this->string(200),
            'company' => $this->string(200)->notNull(),
            'tax_code' => $this->string(20)->notNull(),
            'balance' => $this->integer()->notNull()->defaultValue(0),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%contact}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'phone' => $this->string(20)->notNull(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->string(255),
        ], $tableOptions);

        $this->createTable('{{%contact_group}}', [
            'id' => $this->primaryKey(),
            'group_id' => $this->string(255)->notNull(),
            'contact_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%group}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'description' => $this->string(255),
            'user_id' => $this->integer()->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createTable('{{%dialer}}', [
            'id' => $this->primaryKey(),
            'number' => $this->string(20)->notNull(),
            'extend' => $this->string(10)->notNull(),
            'domain' => $this->string(100)->notNull(),
            'action' => $this->string(10)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(1),
        ], $tableOptions);

        $this->createTable('{{%customer_dialer}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'dialer_id' => $this->integer()->notNull(),
            'call' => $this->integer()->defaultValue(null),
            'viettel' => $this->integer()->defaultValue(null),
            'mobifone' => $this->integer()->defaultValue(null),
            'vinaphone' => $this->integer()->defaultValue(null),
            'vinamobile' => $this->integer()->defaultValue(null),
            'gmobile' => $this->integer()->defaultValue(null),
            'other' => $this->integer()->defaultValue(null),
        ], $tableOptions);
        $this->createTable('{{%transaction_history}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->integer()->notNull(),
            'description' => $this->string(200)->notNull(),
            'transaction_type' => $this->string(1)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull()
        ], $tableOptions);

        $form = new \backend\forms\SignupForm([
            'name' => 'Administrator',
            'username' => 'admin',
            'email' => 'phamngocson1988@gmail.com',
            'password' => '123456'
        ]);
        $user = $form->signup();
        
        $auth = Yii::$app->authManager;
        $auth->removeAll();
        $admin = $auth->createRole('admin');
        $admin->description = 'Admin';
        $auth->add($admin);
        $auth->assign($admin, $user->id);

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

        $this->createTable('{{%record}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(100)->notNull(),
            'dialer_id' => $this->integer(100)->notNull(),
            'dialer_type' => $this->string(10)->notNull(),
            'start_time' => $this->dateTime(),
            'end_time' => $this->dateTime(),
            'message' => $this->text(),
            'phone' => $this->string(50)->notNull(),
            'status' => $this->string(10)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ], $tableOptions);
        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%record}} MODIFY `dialer_type` ENUM('sms', 'call') NOT NULL";
            $command = $this->db->createCommand($alter);
            $command->execute();

            $alterStatus = "ALTER TABLE {{%record}} MODIFY `status` ENUM('requesting','calling','end') NOT NULL DEFAULT 'requesting'";
            $commandStatus = $this->db->createCommand($alterStatus);
            $commandStatus->execute();
        }
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
        // $this->dropTable('{{%customer}}');
        $this->dropTable('{{%image}}');
        $this->dropTable('{{%file}}');
        // $this->dropTable('{{%profile}}');
        $this->dropTable('{{%transaction_history}}');
        $this->dropTable('{{%dailer}}');
        $this->dropTable('{{%customer_dailer}}');
        $this->dropTable('{{%record}}');
        $this->dropTable('{{%group}}');
        $this->dropTable('{{%contact_group}}');
    }
}
