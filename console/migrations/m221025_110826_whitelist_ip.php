<?php

use yii\db\Migration;

/**
 * Class m221025_110826_whitelist_ip
 */
class m221025_110826_whitelist_ip extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%whitelist_ip}}', [
            'ip' => $this->string(50),
            'name' => $this->string(50)->notNull(),
            'status' => $this->smallInteger()->notNull()->defaultValue(0), // 10 for active, 5 for disactive 
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(11),
        ], $tableOptions);
        $this->addPrimaryKey('whitelist_ip_pk', '{{%whitelist_ip}}', ['ip']);
    }

    public function down()
    {
        echo "whitelist_ip cannot be reverted.\n";

        $this->dropTable('{{%whitelist_ip}}');
        return false;
    }
}
