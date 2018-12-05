<?php

use yii\db\Migration;

/**
 * Class m180910_062222_create_staff
 */
class m180910_062222_create_staff extends Migration
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
        echo "m180910_062222_create_staff cannot be reverted.\n";

        return false;
    }

    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%department}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'branch' => $this->string(10)->comment('Enum: hochiminh, phanrang'),//enum (hochiminh, phanrang)
            'phone' => $this->string(50),
            'parent_id' => $this->integer(11),
        ], $tableOptions);

        $this->createTable('{{%staff}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(255)->notNull(),
            'avatar' => $this->integer(11),
            'email' => $this->string()->notNull()->unique(),
            'phone' => $this->string(50),
            'address' => $this->string(200),
            'birthday' => $this->date(50),
            'gender' => $this->string(1)->comment('Enum: M,F'),//M,F
            'description' => $this->string(200),
            'department_id' => $this->integer(11),
            'start_date' => $this->date(),
            'end_date' => $this->date(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $alterDepartment = "ALTER TABLE {{%department}} MODIFY `branch` ENUM('hochiminh', 'phanrang') NOT NULL DEFAULT 'hochiminh'";
            $commandDepartment = $this->db->createCommand($alterDepartment);
            $commandDepartment->execute();

            $alterStaff = "ALTER TABLE {{%staff}} MODIFY `gender` ENUM('M', 'F') NOT NULL DEFAULT 'M'";
            $commandStaff = $this->db->createCommand($alterStaff);
            $commandStaff->execute();
        }
    }

    public function down()
    {
        $this->dropTable('{{%staff}}');
        $this->dropTable('{{%department}}');
    }
}
