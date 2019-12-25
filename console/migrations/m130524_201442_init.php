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

        $this->createTable('{{%user_favorite}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'favorite_game_id' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createTable('{{%user_refer}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'email' => $this->string(255)->notNull(),
            'name' => $this->string(255),
            'status' => $this->string(255),
            'note' => $this->string(255),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'payment_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%user_affiliate}}', [
            'user_id' => $this->integer()->notNull(),
            'preferred_im' => $this->string(50)->notNull(),
            'im_account' => $this->string(255)->notNull(),
            'company' => $this->string()->notNull(),
            'channel' => $this->string(),
            'channel_type' => $this->string(50)->notNull(), // set 1: pending, 2: completed
            'status' => $this->integer()->defaultValue(1),
            'code' => $this->string(50),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%user_commission}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'commission' => $this->float()->notNull()->defaultValue(0),
            'order_id' => $this->integer()->notNull(),
            'member_id' => $this->integer()->notNull(),
            'description' => $this->string(255),
            'created_at' => $this->date(),
            'valid_from_date' => $this->date(),
            'valid_to_date' => $this->date(),
            'status' => $this->integer()->notNull()->defaultValue(1), // set 1: valid, 2: withdrawed
        ], $tableOptions);

        $this->createTable('{{%user_commission_withdraw}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'amount' => $this->float()->notNull()->defaultValue(0),
            'created_at' => $this->dateTime()->notNull(),
            'approved_at' => $this->dateTime(),
            'approved_by' => $this->integer(),
            'executed_at' => $this->dateTime(),
            'executed_by' => $this->integer(),
            'note' => $this->string(255),
            'evidence' => $this->integer(),
            'status' => $this->integer()->notNull()->defaultValue(1), // set 1: request, 2: approved, 3: executed
        ], $tableOptions);

        $this->createTable('{{%user_reseller}}', [
            'user_id' => $this->integer()->notNull(),
            'level' => $this->integer()->notNull(),
            'code' => $this->string(50)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'manager_id' => $this->integer(),
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
        $auth->assign($admin, 1);

        /* Post table */
        $this->createTable('{{%post}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'slug' => $this->string(100)->notNull()->unique(),
            'excerpt' => $this->string(100),
            'content' => $this->text()->notNull(),
            'image_id' => $this->integer(),
            'type' => $this->string()->defaultValue('post')->comment('enum: post,product')->notNull(),
            'meta_title' => $this->string(160),
            'meta_keyword' => $this->string(160),
            'meta_description' => $this->string(160),
            'status' => $this->string()->comment('Enum: Y,N,D')->defaultValue('Y')->notNull(),
            'position' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
        ], $tableOptions);

        /* Category table */
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(100)->notNull(),
            'type' => $this->string(),
            'slug' => $this->string(100)->notNull()->unique(),
            'parent_id' => $this->string(),
            'image_id' => $this->integer(),
            'meta_title' => $this->string(160),
            'meta_keyword' => $this->string(160),
            'meta_description' => $this->string(160),
            'icon' => $this->string(50),
            'visible' => $this->string(1)->comment('enum: Y,N')->defaultValue('Y'),
        ], $tableOptions);

        /* Post Category table */
        $this->createTable('{{%post_category}}', [
            'post_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'is_main' => $this->string(1)->comment('Enum: Y,N')->defaultValue('N')->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('post_category_pk', '{{%post_category}}', ['post_id', 'category_id']);

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
        $this->dropTable('{{%user_favorite}}');
        $this->dropTable('{{%user_refer}}');
        $this->dropTable('{{%post_category}}');
        $this->dropTable('{{%post}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%image}}');
        $this->dropTable('{{%user_commission}}');
        $this->dropTable('{{%user_commission_withdraw}}');
        $this->dropTable('{{%user_reseller}}');
    }
}
