<?php

use yii\db\Migration;

/**
 * Class m190121_072745_create_realestate_system
 */
class m190121_072745_create_realestate_system extends Migration
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
        $this->createTable('{{%realestate}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(100)->notNull(),
            'slug' => $this->string(100)->notNull()->unique(),
            'excerpt' => $this->string(200),
            'content' => $this->text(),
            'image_id' => $this->integer(),
            'address' => $this->string(200),
            // 'province_id' => $this->integer(),
            // 'district_id' => $this->integer(),
            // 'ward_id' => $this->integer(),
            // 'direction' => $this->string(1)->comment('Enum: D,T,N,B'),
            // 'area' => $this->integer(),
            // 'price' => $this->integer(),
            'latitude' => $this->float(20,15),
            'longitude' => $this->float(20,15),
            // 'num_bed' => $this->integer(),
            // 'num_toilet' => $this->integer(),
            // 'deposit' => $this->integer(),
            // 'deposit_duration' => $this->integer(), // day
            // 'open_at' => $this->dateTime(),
            // 'close_at' => $this->dateTime(),

            'meta_title' => $this->string(160),
            'meta_keyword' => $this->string(160),
            'meta_description' => $this->string(160),
            'electric_name' => $this->string(100),
            'electric_data' => $this->text(),
            'water_name' => $this->string(100),
            'water_data' => $this->text(),
            // 'status' => $this->string()->comment('Enum: Y,N,D')->defaultValue('Y')->notNull(),
            'created_at' => $this->dateTime(),            
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->dateTime(),
            'deleted_by' => $this->integer(),
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            // $alter = "ALTER TABLE {{%realestate}} MODIFY `status` ENUM('incoming', 'selling', 'soldout') NULL";
            // $alterDirection = "ALTER TABLE {{%realestate}} MODIFY `direction` ENUM('D', 'T', 'N', 'B') NULL";
            // $command = $this->db->createCommand($alter);
            // $command = $this->db->createCommand($alterDirection);
            // $command->execute();
        }

        $this->createTable('{{%room}}', [
            'id' => $this->primaryKey(),
            'code' => $this->string(50)->notNull(),
            'title' => $this->string(100)->notNull(),
            'content' => $this->text(),
            'image_id' => $this->integer(),
            'realestate_id' => $this->integer()->notNull(),
            'price' => $this->integer()->defaultValue(0),
            'status' => $this->string()->comment('Enum: available,rent')->defaultValue('available')->notNull(),
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $alter = "ALTER TABLE {{%room}} MODIFY `status` ENUM('available', 'rent') NULL";
            $command = $this->db->createCommand($alter);
            $command->execute();
        }

        $this->createTable('{{%realestate_image}}', [
            'id' => $this->primaryKey(),
            'realestate_id' => $this->integer(),
            'image_id' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%service}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255),
        ], $tableOptions);

        $this->createTable('{{%realestate_service}}', [
            'id' => $this->primaryKey(),
            'service_id' => $this->integer()->notNull(),
            'realestate_id' => $this->integer()->notNull(),
            'price' => $this->integer(),
        ], $tableOptions);

        $this->createTable('{{%room_service}}', [
            'id' => $this->primaryKey(),
            'room_id' => $this->integer()->notNull(),
            'realestate_service_id' => $this->integer()->notNull(),
            'price' => $this->integer()->notNull()->defaultValue(0),
            'apply' => $this->integer()->notNull()->defaultValue(0),
        ], $tableOptions);

        $this->createTable('{{%contract}}', [
            'id' => $this->primaryKey(),
            'room_id' => $this->integer()->notNull(),
            'customer_name' => $this->string(255)->notNull(),
            'customer_email' => $this->string(255)->notNull(),
            'customer_phone' => $this->string(255)->notNull(),
            'customer_identifier' => $this->string(255)->notNull(),
            'start_date' => $this->dateTime()->notNull(),
            'end_date' => $this->dateTime(),
            'people' => $this->integer(),
            'created_at' => $this->dateTime()->notNull(),
            'created_by' => $this->integer()->notNull(),
        ], $tableOptions);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m190121_072745_create_realestate_system cannot be reverted.\n";
        $this->dropTable('{{%realestate}}');
        $this->dropTable('{{%realestate_image}}');
        $this->dropTable('{{%service}}');
        $this->dropTable('{{%realestate_service}}');
        $this->dropTable('{{%room}}');
        $this->dropTable('{{%room_service}}');
        return false;
    }

    /*
    // Use up()/down() to run migration code without a transaction.
    public function up()
    {

    }

    public function down()
    {
        echo "m190121_072745_create_realestate_system cannot be reverted.\n";

        return false;
    }
    */
}
