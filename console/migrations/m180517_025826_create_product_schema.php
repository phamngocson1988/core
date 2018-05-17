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
            'created_at' => $this->integer(),            
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ], $tableOptions);

        /* Product category table */
        $this->createTable('{{%product_category}}', [
            'product_id' => $this->primaryKey(),
            'category_id' => $this->primaryKey(),
            'is_main' => $this->string(1)->comment('Enum: Y,N')->defaultValue('N')->notNull(),
        ], $tableOptions);

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
