<?php

use yii\db\Migration;

/**
 * Class m190313_163324_create_wallet
 */
class m190313_163324_create_wallet extends Migration
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

        $this->createTable('{{%user_wallet}}', [
            'id' => $this->primaryKey(),
            'amount' => $this->integer(11)->notNull()->defaultValue(0),
            'type' => $this->string(1)->notNull(),
            'description' => $this->string(100), 
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
            'created_user_id' => $this->integer(11),
            'customer_id' => $this->integer(11)->notNull(),
            'status' => $this->string(10)->notNull()->defaultValue('pending'),
        ]);

        $this->createTable('{{%pricing_coin}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'slug' => $this->string(100)->notNull(),
            'description' => $this->string(500),
            'num_of_coin' => $this->integer(11)->notNull(),
            'amount' => $this->integer(11)->notNull()->defaultValue(0),
            'is_best' => $this->string(1)->notNull()->defaultValue('Y'),
            'status' => $this->string(1)->notNull()->defaultValue('Y'),
        ]);

        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%user_wallet}} MODIFY `type` ENUM('I', 'O') NOT NULL";
            $command = $this->db->createCommand($alter);
            $command->execute();

            $alter = "ALTER TABLE {{%user_wallet}} MODIFY `status` ENUM('pending', 'completed') NOT NULL";
            $command = $this->db->createCommand($alter);
            $command->execute();

            $alter = "ALTER TABLE {{%pricing_coin}} MODIFY `is_best` ENUM('Y', 'N') NOT NULL";
            $command = $this->db->createCommand($alter);
            $command->execute();
            $alter = "ALTER TABLE {{%pricing_coin}} MODIFY `status` ENUM('Y', 'N') NOT NULL";
            $command = $this->db->createCommand($alter);
            $command->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190313_163324_create_wallet cannot be reverted.\n";
        $this->dropTable('{{%user_wallet}}');
        $this->dropTable('{{%pricing_coin}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190313_163324_create_wallet cannot be reverted.\n";

        return false;
    }
    */
}
