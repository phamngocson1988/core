<?php

use yii\db\Migration;

/**
 * Class m200725_070025_hotnew
 */
class m200725_070025_hotnew extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%hotnew}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'link' => $this->string(100)->notNull()->unique(),
            'image_id' => $this->integer(),
            'created_at' => $this->integer(),
            'created_by' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        echo "m200613_083530_affiliate cannot be reverted.\n";

        $this->dropTable('{{%hotnew}}');
        return false;
    }
}
