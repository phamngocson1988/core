<?php

use yii\db\Migration;

/**
 * Class m200604_153915_flashsale
 */
class m200604_153915_flashsale extends Migration
{
    public function up()
    {
        $tableOptions = null;

        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        // flashsale
        $this->createTable('{{%flashsale}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'start_from' => $this->datetime()->notNull(),
            'start_to' => $this->datetime()->notNull(),
            'visible' => $this->smallInteger()->notNull()->defaultValue(10),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_by' => $this->integer(),  
            'updated_by' => $this->integer(),  
            'created_at' => $this->dateTime(),            
            'updated_at' => $this->dateTime(),       
        ], $tableOptions);

        $this->createTable('{{%flashsale_game}}', [
            'id' => $this->primaryKey(),
            'flashsale_id' => $this->integer()->notNull(),
            'game_id' => $this->integer()->notNull(),
            'price' => $this->float()->notNull(),
            'limit' => $this->integer(),
        ], $tableOptions);

    }

    /**
     * Drop table `flashsale`
     */
    public function down()
    {
        $this->dropTable('{{%flashsale}}');
        $this->dropTable('{{%flashsale_game}}');
    }
}
