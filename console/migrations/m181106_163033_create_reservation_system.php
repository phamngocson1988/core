<?php

use yii\db\Migration;

/**
 * Class m181106_163033_create_reservation_system
 */
class m181106_163033_create_reservation_system extends Migration
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

        /* Room table */
        $this->createTable('{{%room}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(50)->notNull(),
            'descripition' => $this->string(200),
            'image_id' => $this->integer(),
            'price' => $this->integer(),
            'status' => $this->string()->comment('Enum: Y,N,D')->defaultValue('Y')->notNull(),
            'created_at' => $this->dateTime()->notNull(),            
            'created_by' => $this->integer()->notNull(),            
        ], $tableOptions);
        $this->createTable('{{%room_available}}', [
            'id' => $this->primaryKey(),
            'room_id' => $this->integer(),
            'from_date' => $this->date(),
            'to_date' => $this->date(),
            'available' => $this->boolean()
        ], $tableOptions);
        if ($this->db->driverName === 'mysql') {
            $alterStatus = "ALTER TABLE {{%room}} MODIFY `status` ENUM('Y', 'N', 'D') NOT NULL DEFAULT 'Y'";
            $command = $this->db->createCommand($alterStatus);
            $command->execute();
        }

        /* Booking table */
        $this->createTable('{{%booking}}', [
            'id' => $this->primaryKey(),
            'from_date' => $this->date()->notNull(),
            'to_date' => $this->date()->notNull(),
            'customer_id' => $this->integer(),
            'customer_name' => $this->string(100)->notNull(),
            'customer_email' => $this->string(100),
            'customer_phone' => $this->string(50)->notNull(),
            'customer_identify' => $this->string(50),
            'total_price' => $this->integer(),
            'total_paid' => $this->integer(),
            'booking_status' => $this->string(1)->comment('Enum: Y,N,D')->defaultValue('N')->notNull(),
            'payment_status' => $this->string(1)->comment('Enum: Y,N,D')->defaultValue('N')->notNull(),
            'note' => $this->string(200),
            'created_at' => $this->dateTime()->notNull(),            
            'created_by' => $this->integer()->notNull(),            
        ], $tableOptions);
        $this->createTable('{{%booking_room}}', [
            'id' => $this->primaryKey(),
            'booking_id' => $this->integer()->notNull(),
            'booking_date' => $this->date()->notNull(),
            'room_identify' => $this->integer()->notNull(),
            'sub_price' => $this->integer(),
            'total_price' => $this->integer(),
            'note' => $this->string(200),
        ], $tableOptions);
        if ($this->db->driverName === 'mysql') {
            $alterBookingStatus = "ALTER TABLE {{%booking}} MODIFY `booking_status` ENUM('booked', 'taken', 'finish') NOT NULL DEFAULT 'booked'";
            $alterPaymentStatus = "ALTER TABLE {{%booking}} MODIFY `payment_status` ENUM('pending', 'part', 'complete') NOT NULL DEFAULT 'pending'";
            $command = $this->db->createCommand($alterBookingStatus);
            $command->execute();
            $command = $this->db->createCommand($alterPaymentStatus);
            $command->execute();
        }

        /* Season table */
        $this->createTable('{{%season}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(200)->notNull(),
            'descripition' => $this->text(),
            'apply_discount' => $this->boolean()->defaultValue(true),
            'status' => $this->string(1)->comment('Enum: Y,N')->defaultValue('Y')->notNull(),
            'created_at' => $this->dateTime()->notNull(),            
            'created_by' => $this->integer()->notNull(),            
        ], $tableOptions);
        $this->createTable('{{%season_range}}', [
            'id' => $this->primaryKey(),
            'season_id' => $this->integer()->notNull(),
            'range_type' => $this->string(10)->comment('Enum: every_week, every_month, every_year, specified_date, special_date')->defaultValue('every_week')->notNull(),
            'range_data' => $this->text(),
            'prev_date' => $this->integer(),
            'next_date' => $this->integer(),
            'apply_discount' => $this->boolean()->defaultValue(true),
            'status' => $this->string(1)->comment('Enum: Y,N')->defaultValue('Y')->notNull(),
            'note' => $this->string(200),
        ], $tableOptions);
        $this->createTable('{{%room_season}}', [
            'id' => $this->primaryKey(),
            'room_id' => $this->integer()->notNull(),
            'season_id' => $this->integer(),
            'price' => $this->integer(),
            'status' => $this->string(1)->comment('Enum: Y,N')->defaultValue('Y')->notNull(),
            'note' => $this->string(200),
        ], $tableOptions);
        if ($this->db->driverName === 'mysql') {
            $alterSeasonStatus = "ALTER TABLE {{%season}} MODIFY `status` ENUM('Y', 'N') NOT NULL DEFAULT 'Y'";
            $alterSeasonRangeType = "ALTER TABLE {{%season_range}} MODIFY `range_type` ENUM('every_week', 'every_month', 'every_year', 'specified_date', 'special_date') NOT NULL DEFAULT 'every_week'";
            $alterSeasonRangeStatus = "ALTER TABLE {{%season_range}} MODIFY `status` ENUM('Y', 'N') NOT NULL DEFAULT 'Y'";
            $command = $this->db->createCommand($alterSeasonStatus);
            $command->execute();
            $command = $this->db->createCommand($alterSeasonRangeType);
            $command->execute();
            $command = $this->db->createCommand($alterSeasonRangeStatus);
            $command->execute();
        }
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        echo "m181106_163033_create_reservation_system cannot be reverted.\n";
        $this->dropTable('{{%room}}');
        $this->dropTable('{{%room_available}}');
        $this->dropTable('{{%booking}}');
        $this->dropTable('{{%booking_room}}');
        $this->dropTable('{{%season}}');
        $this->dropTable('{{%season_range}}');
        $this->dropTable('{{%room_season}}');
        return false;
    }

}
