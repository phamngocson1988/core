<?php

use yii\db\Migration;

/**
 * Class m180919_083240_create_product_package
 */
class m180919_083240_create_product_package extends Migration
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

        /* Product table */
        $this->createTable('{{%product_package}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'product_id' => $this->integer(),
            'image_id' => $this->integer(),
            'price' => $this->integer(),
            'sale_price' => $this->integer(),
            'sale_off_type' => $this->string()->comment('Enum: fix, percent')->defaultValue('fix')->notNull(),
            'sale_off_from' => $this->dateTime(),
            'sale_off_to' => $this->dateTime(),
            'status' => $this->string()->comment('Enum: Y,N,D')->defaultValue('Y')->notNull(),
            'created_at' => $this->integer(),            
            'created_by' => $this->integer(),
            'updated_at' => $this->integer(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->integer(),
            'deleted_by' => $this->integer(),
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%product_package}} 
                        MODIFY `sale_off_type` ENUM('fix', 'percent') NOT NULL DEFAULT 'fix',
                        MODIFY `status` ENUM('Y', 'N', 'D') NOT NULL DEFAULT 'Y'";
            $command = $this->db->createCommand($alter);
            $command->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%product_package}}');

        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m180919_083240_create_product_package cannot be reverted.\n";

        return false;
    }
    */
}
