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
            'firstname' => $this->string(255),
            'lastname' => $this->string(255),
            'username' => $this->string()->notNull()->unique(),
            'avatar' => $this->integer(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string()->notNull(),
            'password_reset_token' => $this->string()->unique(),
            'access_token' => $this->string(512),
            'email' => $this->string()->notNull()->unique(),
            'country_code' => $this->string(10),
            'subscription' => $this->integer()->defaultValue(0),
            'phone' => $this->string(50),
            'security_pin' => $this->string(10),
            'is_verify_phone' => $this->smallInteger()->defaultValue(0),
            'is_verify_email' => $this->smallInteger()->defaultValue(0),
            'address' => $this->string(200),
            'birthday' => $this->date(),
            'favorite' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'refer_code' => $this->string(10),
            'referred_by' => $this->integer(),
            'affiliated_with' => $this->integer(),
            'is_reseller' => $this->smallInteger()->defaultValue(1),
            'old_reseller_level' => $this->smallInteger(),
            'reseller_level' => $this->smallInteger()->defaultValue(1),
            'saler_id' => $this->integer(),
            'saler_code' => $this->string(50),
            'is_supplier' => $this->smallInteger()->defaultValue(1),
            'marketing_id' => $this->integer(),
            'trust' => $this->string(1),
            'social_facebook' => $this->string(255),
            'social_twitter' => $this->string(255),
            'social_whatsapp' => $this->string(255),
            'social_telegram' => $this->string(255),
            'social_wechat' => $this->string(255),
            'social_other' => $this->string(255),
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

        

        $this->createTable('{{%user_reseller}}', [
            'user_id' => $this->integer()->notNull(),
            'level' => $this->integer()->notNull(),
            'old_level' => $this->integer(),
            'task_code' => $this->string(128),
            'code' => $this->string(50)->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'level_updated_at' => $this->dateTime(),
            'level_updated_by' => $this->integer(),
            'manager_id' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%reseller_price}}', [
            'reseller_id' => $this->integer()->notNull(),
            'game_id' => $this->integer()->notNull(),
            'price' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'invalid_at' => $this->dateTime(),
        ], $tableOptions);
        $this->addPrimaryKey('reseller_price_pk', '{{%reseller_price}}', ['reseller_id', 'game_id']);


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
            'title' => $this->string(512)->notNull(),
            'slug' => $this->string(512)->notNull(),
            'excerpt' => $this->string(512),
            'table_index' => $this->text(),
            'content' => $this->text()->notNull(),
            'image_id' => $this->integer(),
            'type' => $this->string()->defaultValue('post')->comment('enum: post,product')->notNull(),
            'meta_title' => $this->string(512),
            'meta_keyword' => $this->string(512),
            'meta_description' => $this->string(512),
            'status' => $this->smallInteger()->notNull()->defaultValue(10), // Y: 10, N: 5, D: 1
            'hot' => $this->smallInteger()->defaultValue(0),// 1||0
            'view_count' => $this->integer()->defaultValue(0),
            'author' => $this->string(512),
            'position' => $this->integer(),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime()->notNull(),
            'published_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%post_like}}', [
            'post_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);
        $this->addPrimaryKey('post_like_pk', '{{%post_like}}', ['post_id', 'user_id']);

        $this->createTable('{{%post_rating}}', [
            'post_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
            'rating' => $this->integer()->notNull()->default(1),
        ], $tableOptions);
        // $this->addPrimaryKey('post_rating_pk', '{{%post_rating}}', ['post_id', 'user_id']);

        $this->createTable('{{%post_comment}}', [
            'id' => $this->primaryKey(),
            'post_id' => $this->integer()->notNull(),
            'comment' => $this->text()->notNull(),
            'parent_id' => $this->integer(),
            'created_by' => $this->integer()->notNull(),
            'created_at' => $this->dateTime()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%post_comment_like}}', [
            'comment_id' => $this->integer()->notNull(),
            'user_id' => $this->integer()->notNull(),
        ], $tableOptions);
        // $this->addPrimaryKey('post_comment_like_pk', '{{%post_comment_like}}', ['comment_id', 'user_id']);

        /* Category table */
        $this->createTable('{{%category}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(512)->notNull(),
            'type' => $this->string(),
            'slug' => $this->string(512)->notNull(),
            'parent_id' => $this->integer(),
            'image_id' => $this->integer(),
            'meta_title' => $this->string(512),
            'meta_keyword' => $this->string(512),
            'meta_description' => $this->string(512),
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

        $this->createTable('{{%auth}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'source' => $this->string()->notNull(),
            'source_id' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('fk-auth-user_id-user-id', '{{%auth}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');

        // devices
        $this->createTable('{{%user_devices}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'browser_info' => $this->string()->notNull(),
            'browser_token' => $this->string()->notNull(),
            'last_login' => $this->dateTime()->notNull(),
            'last_login_location' => $this->string()->notNull(),
        ]);

        $this->addForeignKey('fk-devices-user_id-user-id', '{{%user_devices}}', 'user_id', '{{%user}}', 'id', 'CASCADE', 'CASCADE');
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
        $this->dropTable('{{%user_reseller}}');
        $this->dropTable('{{%auth}}');
    }
}
