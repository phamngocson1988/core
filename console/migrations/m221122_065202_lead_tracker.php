<?php

use yii\db\Migration;

/**
 * Class m221122_065202_lead_tracker
 */
class m221122_065202_lead_tracker extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%lead_tracker}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'data' => $this->string(1024),
            'saler_id' => $this->integer(11),
            'country_code' => $this->string(10),
            'phone' => $this->string(50),
            'email' => $this->string(255),
            'channel' => $this->string(255),
            'game' => $this->string(255),
            'is_potential' => $this->boolean()->defaultValue(false),
            'is_target' => $this->boolean()->defaultValue(false),

            'question_1' => $this->boolean()->defaultValue(false),
            'question_2' => $this->boolean()->defaultValue(false),
            'question_3' => $this->boolean()->defaultValue(false),
            'question_4' => $this->boolean()->defaultValue(false),
            'question_5' => $this->boolean()->defaultValue(false),
            'question_6' => $this->boolean()->defaultValue(false),
            'question_7' => $this->boolean()->defaultValue(false),
            'question_8' => $this->boolean()->defaultValue(false),
            'question_9' => $this->boolean()->defaultValue(false),

            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(11),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(11),
        ], $tableOptions);
    }

    public function down()
    {
        echo "lead_tracker cannot be reverted.\n";

        $this->dropTable('{{%lead_tracker}}');
        return false;
    }
}
