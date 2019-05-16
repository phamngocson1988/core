<?php

use yii\db\Migration;

/**
 * Class m180517_025826_create_product_schema
 */
class m180517_025826_create_product_schema extends Migration
{
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
            'unit_name' => $this->string(50),
            'content' => $this->text()->notNull(),
            'image_id' => $this->integer(),
            'price' => $this->integer()->notNull(),
            'pack' => $this->integer()->notNull()->defaultValue(1),
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

        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%game}} MODIFY `status` ENUM('Y', 'N', 'D') NOT NULL DEFAULT 'Y'";
            $command = $this->db->createCommand($alter);
            $command->execute();
        }

        $this->createTable('{{%game_image}}', [
            'id' => $this->primaryKey(),
            'game_id' => $this->integer()->notNull(),
            'image_id' => $this->integer()->notNull()
        ], $tableOptions);

        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'game_id' => $this->integer()->notNull(),
            'image_id' => $this->integer(),
            'price' => $this->integer(),
            'unit' => $this->integer(),
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
            $alter = "ALTER TABLE {{%product}} MODIFY `status` ENUM('Y', 'N', 'D') NOT NULL DEFAULT 'Y'";
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
