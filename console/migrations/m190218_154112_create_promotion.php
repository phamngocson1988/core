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
            'content' => $this->text(),
            'image_id' => $this->integer(11),
            'code' => $this->string(50), 
            'promotion_type' => $this->string(10),//fix,percent
            'value' => $this->integer(11),
            'promotion_scenario' => $this->string(10),//coin,money
            'promotion_direction' => $this->string(10)->defaultValue('up'),//up,down
            'user_using' => $this->integer(11),
            'total_using' => $this->integer(11),
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

            $alterValueType = "ALTER TABLE {{%promotion}} MODIFY `promotion_type` ENUM('fix', 'percent') NOT NULL DEFAULT 'percent'";
            $commandValueType = $this->db->createCommand($alterValueType);
            $commandValueType->execute();

            $alterObjectType = "ALTER TABLE {{%promotion}} MODIFY `promotion_scenario` ENUM('coin', 'money') NOT NULL DEFAULT 'money'";
            $commandObjectType = $this->db->createCommand($alterObjectType);
            $commandObjectType->execute();

            $alterDirection = "ALTER TABLE {{%promotion}} MODIFY `promotion_direction` ENUM('up', 'down') NOT NULL DEFAULT 'up'";
            $commandDirection = $this->db->createCommand($alterDirection);
            $commandDirection->execute();
        }

        $this->createTable('{{%promotion_user}}', [
            'promotion_id' => $this->integer(11)->notNull(),
            'user_id' => $this->integer(11)->notNull(),
            'from_date' => $this->date(),
            'to_date' => $this->date(),
        ]);
        $this->addPrimaryKey('pro-user_pk', '{{%promotion_user}}', ['promotion_id', 'user_id']);

        $this->createTable('{{%promotion_game}}', [
            'promotion_id' => $this->integer(11)->notNull(),
            'game_id' => $this->integer(11)->notNull(),
            'from_date' => $this->date(),
            'to_date' => $this->date(),
        ]);
        $this->addPrimaryKey('pro-game_pk', '{{%promotion_game}}', ['promotion_id', 'game_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190218_154112_create_promotion cannot be reverted.\n";
        $this->dropIndex('idx_unique_code', '{{%promotion}}');
        $this->dropTable('{{%promotion}}');
        $this->dropTable('{{%promotion_user}}');
        $this->dropTable('{{%promotion_game}}');
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
