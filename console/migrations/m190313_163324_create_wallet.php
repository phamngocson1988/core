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
            'type' => $this->string(1)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'coin' => $this->float()->notNull()->defaultValue(0),
            'balance' => $this->float()->notNull()->defaultValue(0),
            'ref_name' => $this->string(100), 
            'ref_key' => $this->string(100), 
            'description' => $this->string(100), 
            'created_by' => $this->integer(11),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
            'payment_at' => $this->dateTime(),
            'status' => $this->string(10)->notNull()->defaultValue('pending'),
        ]);

        $this->createTable('{{%payment_transaction}}', [
            'id' => $this->primaryKey(),
            'auth_key' => $this->string(50)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'user_ip' => $this->string(50),
            'payment_method' => $this->string(50)->notNull(),
            'payment_type' => $this->string(10),
            'payment_id' => $this->string(50)->notNull(),
            'payment_data' => $this->text(),
            'rate_usd' => $this->float(),
            'order_id' => $this->integer(11),
            'description' => $this->string(100), 
            'created_by' => $this->integer(11),
            'updated_by' => $this->integer(11),
            'created_at' => $this->dateTime()->notNull(),
            'updated_at' => $this->dateTime(),
            'payment_at' => $this->dateTime(),
            'price' => $this->float()->notNull()->defaultValue(0),
            'discount_price' => $this->float()->notNull()->defaultValue(0),
            'total_price' => $this->float()->notNull()->defaultValue(0),
            'total_fee' => $this->float()->defaultValue(0),
            'coin' => $this->float()->notNull()->defaultValue(0),
            'promotion_coin' => $this->float()->notNull()->defaultValue(0),
            'total_coin' => $this->float()->notNull()->defaultValue(0),
            'promotion_code' => $this->string(50),
            'promotion_id' => $this->integer(11),
            'evidence' => $this->string(255),
            'remark' => $this->string(255),
            'status' => $this->string(10)->notNull()->defaultValue('pending'),
        ]);

        $this->createTable('{{%pricing_coin}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'slug' => $this->string(100)->notNull(),
            'description' => $this->string(500),
            'num_of_coin' => $this->integer(11)->notNull(),
            'num_of_coin_reseller' => $this->integer(11)->defaultValue(0),
            'amount' => $this->integer(11)->notNull()->defaultValue(0),
            'is_best' => $this->string(1)->notNull()->defaultValue('Y'),
            'status' => $this->string(1)->notNull()->defaultValue('Y'),
        ]);

        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%user_wallet}} MODIFY `type` ENUM('I', 'O') NOT NULL";
            $command = $this->db->createCommand($alter);
            $command->execute();

            $alter1 = "ALTER TABLE {{%user_wallet}} MODIFY `status` ENUM('pending', 'completed', 'waiting') NOT NULL";
            $command1 = $this->db->createCommand($alter1);
            $command1->execute();

            $alter2 = "ALTER TABLE {{%pricing_coin}} MODIFY `is_best` ENUM('Y', 'N') NOT NULL";
            $command2 = $this->db->createCommand($alter2);
            $command2->execute();
            $alter3 = "ALTER TABLE {{%pricing_coin}} MODIFY `status` ENUM('Y', 'N') NOT NULL";
            $command3 = $this->db->createCommand($alter3);
            $command3->execute();
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
        $this->dropTable('{{%payment_transaction}}');
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
