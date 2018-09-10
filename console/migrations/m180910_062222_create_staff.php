<?php

use yii\db\Migration;

/**
 * Class m180910_062222_create_staff
 */
class m180910_062222_create_staff extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180910_062222_create_staff cannot be reverted.\n";

        return false;
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%staff}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'avatar' => $this->integer(11),
            'email' => $this->string()->notNull()->unique(),
            'phone' => $this->string(50),
            'address' => $this->string(200),
            'birthday' => $this->date(50),
            'gender' => $this->string(200),
            'description' => $this->string(1),//M,F
            'department_id' => $this->integer(11),
            'start_date' => $this->date(),
            'end_date' => $this->date(),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%staff}}');
    }
}
