<?php

use yii\db\Migration;

/**
 * Class m200111_111400_bank
 */
class m200111_111400_bank extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%bank}}', [
            'code' => $this->string()->notNull(),
            'short_name' => $this->string(255),
            'name' => $this->string(255),
            'country_code' => $this->string(10)->defaultValue('VN'),
            'status' => $this->string(1),
        ], $tableOptions);
        $this->addPrimaryKey('bank_pk', '{{%bank}}', ['code']);
        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%bank}} MODIFY `status` ENUM('Y', 'N') NOT NULL DEFAULT 'Y'";
            $command = $this->db->createCommand($alter);
            $command->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m200111_111400_bank cannot be reverted.\n";

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m200111_111400_bank cannot be reverted.\n";

        return false;
    }
    */
}
