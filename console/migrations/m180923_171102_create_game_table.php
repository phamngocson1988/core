<?php

use yii\db\Migration;

/**
 * Handles the creation of table `game`.
 */
class m180923_171102_create_game_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('game', [
            'id' => $this->primaryKey(),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('game');
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /* Game table */
        $this->createTable('{{%game}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'slug' => $this->string(100)->notNull()->unique(),
            'excerpt' => $this->string(200),
            'content' => $this->text()->notNull(),
            'image_id' => $this->integer(),
            'meta_title' => $this->string(160),
            'meta_keyword' => $this->string(160),
            'meta_description' => $this->string(160),
            'status' => $this->string()->comment('Enum: Y,N,D')->defaultValue('Y')->notNull(),
            'created_at' => $this->dateTime(),            
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->dateTime(),
            'deleted_by' => $this->integer(),
        ], $tableOptions);

        /* Game image table */
        $this->createTable('{{%game_image}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer(),
            'image_id' => $this->integer(),
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%game}} 
                        MODIFY `status` ENUM('Y', 'N', 'D') NOT NULL DEFAULT 'Y'";
            $command = $this->db->createCommand($alter);
            $command->execute();
        }

        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'game_id' => $this->integer()->notNull(),
            'image_id' => $this->integer(),
            'price' => $this->integer(),
            'gems' => $this->integer(),
            'sale_price' => $this->integer(),
            'sale_off_type' => $this->string()->comment('Enum: fix, percent')->defaultValue('fix')->notNull(),
            'sale_off_from' => $this->dateTime(),
            'sale_off_to' => $this->dateTime(),
            'status' => $this->string()->comment('Enum: Y,N,D')->defaultValue('Y')->notNull(),
            'position' => $this->integer(),            
            'created_at' => $this->dateTime(),            
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->dateTime(),
            'deleted_by' => $this->integer(),
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%product}} 
                        MODIFY `sale_off_type` ENUM('fix', 'percent') NOT NULL DEFAULT 'fix',
                        MODIFY `status` ENUM('Y', 'N', 'D') NOT NULL DEFAULT 'Y'";
            $command = $this->db->createCommand($alter);
            $command->execute();
        }
    }

    public function down()
    {
        $this->dropTable('{{%game}}');
        $this->dropTable('{{%game_image}}');
        $this->dropTable('{{%product}}');
    }
}
