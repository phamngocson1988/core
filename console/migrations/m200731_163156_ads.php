<?php

use yii\db\Migration;

/**
 * Class m200731_163156_ads
 */
class m200731_163156_ads extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%ads}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(255)->notNull(),
            'link' => $this->string(255)->notNull(),
            'media_id' => $this->integer()->notNull(),
            'start_date' => $this->dateTime(),
            'end_date' => $this->dateTime(),
            'status' => $this->integer(),
            'position' => $this->string(15),
            'fee' => $this->float(),
            'currency' => $this->string(15),
            'contact_phone' => $this->string(255)->notNull(),
            'contact_email' => $this->string(255)->notNull(),
            'contact_name' => $this->string(255)->notNull(),
            'created_by' => $this->integer(),
            'updated_by' => $this->integer(),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $tableOptions);

        if ($this->db->driverName === 'mysql') {
            $alterAdsPosition = "ALTER TABLE {{%ads}} MODIFY `position` ENUM('tophome', 'bottomhome', 'bannerhome', 'sidebar') NOT NULL DEFAULT 'tophome'";
            $commandAlter = $this->db->createCommand($alterAdsPosition);
            $commandAlter->execute();
        }
    }

    public function down()
    {
        $this->dropTable('{{%ads}}');
    }
}
