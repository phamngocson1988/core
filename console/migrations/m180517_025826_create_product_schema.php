<?php

use yii\db\Migration;

/**
 * Class m180517_025826_create_product_schema
 */
class m180517_025826_create_product_schema extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {

    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m180517_025826_create_product_schema cannot be reverted.\n";

        return false;
    }

    
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        /* Product table */
        $this->createTable('{{%product}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'slug' => $this->string(100)->notNull()->unique(),
            'excerpt' => $this->string(200),
            'content' => $this->text()->notNull(),
            'image_id' => $this->integer(),
            'price' => $this->integer(),
            'sale_price' => $this->integer(),
            'meta_title' => $this->string(160),
            'meta_keyword' => $this->string(160),
            'meta_description' => $this->string(160),
            'status' => $this->string()->comment('Enum: Y,N,D')->defaultValue('Y')->notNull(),
            'created_at' => $this->dateTime()->notNull(),            
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->dateTime(),
            'deleted_by' => $this->integer(),
        ], $tableOptions);
        if ($this->db->driverName === 'mysql') {
            $alterStatus = "ALTER TABLE {{%product}} MODIFY `status` ENUM('Y', 'N', 'D') NOT NULL DEFAULT 'Y'";
            $command = $this->db->createCommand($alterStatus);
            $command->execute();
        }

        /* Product category table */
        $this->createTable('{{%product_category}}', [
            'product_id' => $this->integer()->notNull(),
            'category_id' => $this->integer()->notNull(),
            'is_main' => $this->string(1)->comment('Enum: Y,N')->defaultValue('N')->notNull(),
        ], $tableOptions);
        if ($this->db->driverName === 'mysql') {
            $alterMain = "ALTER TABLE {{%product_category}} MODIFY `is_main` ENUM('Y', 'N') NOT NULL DEFAULT 'N'";
            $command = $this->db->createCommand($alterMain);
            $command->execute();
        }
        // $this->addPrimaryKey('product_category_pk', '{{%product_category}}', ['product_id', 'category_id']);

        /* Product image table */
        $this->createTable('{{%product_image}}', [
            'product_id' => $this->integer(),
            'image_id' => $this->integer(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%product}}');
        $this->dropTable('{{%product_category}}');
        $this->dropTable('{{%product_image}}');
    }
}
