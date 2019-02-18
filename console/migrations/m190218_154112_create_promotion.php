<?php

use yii\db\Migration;

/**
 * Class m190218_154112_create_promotion
 */
class m190218_154112_create_promotion extends Migration
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

        $this->createTable('{{%promotion}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(500)->notNull(),
            'image_id' => $this->integer(11),
            'code' => $this->string(50), 
            'user_type' => $this->integer(11),
            'value_type' => $this->integer(11),
            'value' => $this->integer(11),
            'available' => $this->integer(11)->defaultValue(null), // the number of using this promotion
            'combination' => $this->boolean()->defaultValue(false), // whether allowing combine with other promotions
            'from_date' => $this->dateTime(),
            'to_date' => $this->dateTime(),
            'created_by' => $this->integer(11),
            'created_at' => $this->dateTime(),
            'updated_by' => $this->integer(11),
            'updated_at' => $this->dateTime(),
            'status' => $this->string()->comment('Enum: Y,N,D')->defaultValue('Y')->notNull(),
        ]);

        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%promotion}} MODIFY `status` ENUM('Y', 'N', 'D') NOT NULL DEFAULT 'Y'";
            $command = $this->db->createCommand($alter);
            $command->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190218_154112_create_promotion cannot be reverted.\n";

        $this->dropTable('{{%promotion}}');
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190218_154112_create_promotion cannot be reverted.\n";

        return false;
    }
    */
}
