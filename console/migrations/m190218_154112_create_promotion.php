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
            'code' => $this->string(50), 
            'value_type' => $this->string(10),//fix,percent
            'value' => $this->integer(11),
            'object_type' => $this->string(10),//coin,money
            'number_of_use' => $this->integer(11),
            'from_date' => $this->date(),
            'to_date' => $this->date(),
            'created_by' => $this->integer(11),
            'created_at' => $this->dateTime(),
            'status' => $this->string()->comment('Enum: Y,N,D')->defaultValue('Y')->notNull(),
        ]);

        $this->createIndex('idx_unique_code', '{{%promotion}}', 'code', true); // unique index
        
        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%promotion}} MODIFY `status` ENUM('Y', 'N') NOT NULL DEFAULT 'Y'";
            $command = $this->db->createCommand($alter);
            $command->execute();

            $alterValueType = "ALTER TABLE {{%promotion}} MODIFY `value_type` ENUM('fix', 'percent') NOT NULL DEFAULT 'percent'";
            $commandValueType = $this->db->createCommand($alterValueType);
            $commandValueType->execute();

            $alterObjectType = "ALTER TABLE {{%promotion}} MODIFY `object_type` ENUM('coin', 'money') NOT NULL DEFAULT 'money'";
            $commandObjectType = $this->db->createCommand($alterObjectType);
            $commandObjectType->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190218_154112_create_promotion cannot be reverted.\n";
        $this->dropIndex('idx_unique_code', '{{%promotion}}');
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
