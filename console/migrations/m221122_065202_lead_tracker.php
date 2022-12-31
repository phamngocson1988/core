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
            // lead tracker
            'id' => $this->primaryKey(),
            'name' => $this->string(255),
            'link' => $this->string(1024),
            'saler_id' => $this->integer(11),
            'country_code' => $this->string(10),
            'phone' => $this->string(50),
            'email' => $this->string(255),
            'channels' => $this->string(255),
            'contacts' => $this->string(255),
            'game_id' => $this->integer(11),
            'is_potential' => $this->boolean()->defaultValue(false),
            'potential_lead_at' => $this->dateTime(),
            'is_target' => $this->boolean()->defaultValue(false),
            'target_lead_at' => $this->dateTime(),
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

            // customer tracker
            'user_id' => $this->integer(11),
            'converted_at' => $this->dateTime(),
            'converted_by' => $this->integer(11),
            'registered_at' => $this->dateTime(),
            // sale performance
            'first_order_at' => $this->dateTime(),
            'is_normal_customer' => $this->boolean()->defaultValue(false),
            'normal_customer_at' => $this->dateTime(),
            'sale_month_1' => $this->float(),
            'sale_month_2' => $this->float(),
            'sale_month_3' => $this->float(),
            'sale_target' => $this->text(),
            'growth_rate_1' => $this->float(),
            'growth_rate_2' => $this->float(),
            'growth_speed' => $this->float(), // growth_rate_1 - growth_rate_2
            'is_potential_customer' => $this->boolean()->defaultValue(false),
            'potential_customer_at' => $this->dateTime(),

            // key customer
            'sale_growth' => $this->boolean()->defaultValue(false),
            'number_of_game' => $this->integer(11),
            'product_growth' => $this->boolean()->defaultValue(false),
            'kpi_growth' => $this->float(),
            'is_key_customer' => $this->boolean()->defaultValue(false),
            'key_customer_at' => $this->dateTime(),

            'monthly_sale_volumn' => $this->float(),
            'daily_sale_volumn' => $this->float(),

            'is_loyalty' => $this->boolean()->defaultValue(false), // has order in every last 6 months
            'loyalty_customer_at' => $this->dateTime(),
            'is_dangerous' => $this->boolean()->defaultValue(false),
            'dangerous_customer_at' => $this->dateTime(),
            'customer_tracker_status' => $this->integer(11),
            'customer_monthly_status' => $this->integer(11),

        ], $tableOptions);
        $this->createTable('{{%lead_tracker_comment}}', [
            'id' => $this->primaryKey(),
            'lead_tracker_id' => $this->integer(11)->notNull(),
            'content' => $this->text(),
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(11),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(11),
        ], $tableOptions);
        $this->createTable('{{%lead_tracker_action_log}}', [
            'id' => $this->primaryKey(),
            'lead_tracker_id' => $this->integer(11)->notNull(),
            'action' => $this->string(50)->notNull(),
            'content' => $this->text(),
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
        $this->dropTable('{{%lead_tracker_comment}}');
        return false;
    }
}
