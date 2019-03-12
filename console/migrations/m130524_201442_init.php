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
            'phone' => $this->string(50),
            'address' => $this->string(200),
            'birthday' => $this->integer(),
            'favorite' => $this->integer(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'affiliate_code' => $this->string(10),
            'invited_by' => $this->integer(),
            'is_reseller' => $this->smallInteger()->defaultValue(0),
            'saler_id' => $this->integer(),
            'marketing_id' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%user_favorite}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'favorite_game_id' => $this->integer()->notNull()
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
    }

    public function down()
    {
        $this->dropTable('{{%user}}');
        $this->dropTable('{{%user_favorite}}');
        $this->dropTable('{{%post_category}}');
        $this->dropTable('{{%post}}');
        $this->dropTable('{{%category}}');
        $this->dropTable('{{%image}}');
    }
}
