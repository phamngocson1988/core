<?php

use yii\db\Migration;

/**
 * Class m181106_163033_create_reservation_system
 */
class m181106_163033_create_reservation_system extends Migration
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

        /* Product table */
        $this->createTable('{{%room}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer(),
            'title' => $this->string(50)->notNull(),
            'descripition' => $this->string(200),
            'image_id' => $this->integer(),
            'price' => $this->integer(),
            'status' => $this->string()->comment('Enum: Y,N,D')->defaultValue('Y')->notNull(),
            'created_at' => $this->dateTime()->notNull(),            
        ], $tableOptions);
        if ($this->db->driverName === 'mysql') {
            $alterStatus = "ALTER TABLE {{%room}} MODIFY `status` ENUM('Y', 'N', 'D') NOT NULL DEFAULT 'Y'";
            $command = $this->db->createCommand($alterStatus);
            $command->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181106_163033_create_reservation_system cannot be reverted.\n";
        $this->dropTable('{{%room}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m181106_163033_create_reservation_system cannot be reverted.\n";

        return false;
    }
    */
}
