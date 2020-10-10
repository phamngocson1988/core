<?php

use yii\db\Migration;

/**
 * Class m200608_150429_complain
 */
class m200608_150429_complain extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%complain}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'operator_id' => $this->integer()->notNull(),
            'managed_by' => $this->integer(),
            'reason_id' => $this->integer()->notNull(),
            'title' => $this->string(255)->notNull(),
            'slug' => $this->string(255)->notNull(),
            'description' => $this->text()->notNull(),
            'account_name' => $this->string(255)->notNull(),
            'account_email' => $this->string(255)->notNull(),
            'status' => $this->integer()->notNull(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
            'first_reply_at' => $this->dateTime(),
        ], $tableOptions);
        if ($this->db->driverName === 'mysql') {
            $alterComplainStatus = "ALTER TABLE {{%complain}} MODIFY `status` ENUM('open', 'reject', 'resolve') NOT NULL DEFAULT 'open'";
            $command = $this->db->createCommand($alterComplainStatus);
            $command->execute();
        }

        $this->createTable('{{%complain_reply}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'complain_id' => $this->integer()->notNull(),
            'operator_id' => $this->integer()->notNull(),
            'description' => $this->text()->notNull(),
            'mark_close' => $this->boolean()->notNull()->defaultValue(false),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        $this->createTable('{{%complain_follow}}', [
            'id' => $this->primaryKey(),
            'user_id' => $this->integer()->notNull(),
            'complain_id' => $this->integer()->notNull(),
        ], $tableOptions);

        $this->createTable('{{%complain_reason}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
        ], $tableOptions);

        $this->createTable('{{%complain_file}}', [
            'id' => $this->primaryKey(),
            'complain_id' => $this->integer()->notNull(),
            'file_id' => $this->string(255)->notNull(),
        ], $tableOptions);
    }

    public function down()
    {
        $this->dropTable('{{%complain}}');
        $this->dropTable('{{%complain_reason}}');
        $this->dropTable('{{%complain_file}}');
        $this->dropTable('{{%complain_reply}}');
        $this->dropTable('{{%complain_follow}}');
    }
}
