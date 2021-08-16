<?php

use yii\db\Migration;

/**
 * Class m210109_092832_payment_info
 */
class m210109_092832_payment_info extends Migration
{
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            // http://stackoverflow.com/questions/766809/whats-the-difference-between-utf8-general-ci-and-utf8-unicode-ci
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }

        $this->createTable('{{%payment_reality}}', [
            'id' => $this->primaryKey(),
            'payment_commitment_id' => $this->string(255),
            'payer' => $this->string(255),
            'payment_id' => $this->string(255),
            'paygate' => $this->string(255)->notNull(),
            'payment_type' => $this->string(10), // online, offline
            'payment_data' => $this->text(),
            'payment_time' => $this->dateTime(),
            'payment_note' => $this->string(255), // it could be a comment from customer
            'total_amount' => $this->float(),
            'currency' => $this->string(50),
            'kingcoin' => $this->float(),
            'exchange_rate' => $this->float(),
            'note' => $this->string(255), // this field saves staff comment, when he proceed this payment information
            'evidence' => $this->string(255),
            'user_id' => $this->integer(), // ref to User table: this payment used for which user
            'status' => $this->smallInteger()->notNull()->defaultValue(5), // 10 for complete, 5 for pending, 1 for deleted 
            'object_name' => $this->string(255),
            'object_key' => $this->integer(),
            'object_created_at' => $this->dateTime(),
            'confirmed_by' => $this->integer(),
            'confirmed_at' => $this->dateTime(),
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
            'deleted_at' => $this->dateTime(),
            'deleted_by' => $this->integer(),
            'deleted_note' => $this->string(255),
        ], $tableOptions);

        // creates index for column `object_name`
        $this->createIndex(
            'idx-object-name',
            '{{%payment_reality}}',
            'object_name'
        );
        // creates index for column `object_key`
        $this->createIndex(
            'idx-object-key',
            '{{%payment_reality}}',
            'object_key'
        );

        $this->createTable('{{%payment_commitment}}', [
            'id' => $this->primaryKey(),
            'payment_reality_id' => $this->string(255),
            'payment_id' => $this->string(255),
            'paygate' => $this->string(255)->notNull(),
            'payment_type' => $this->string(10), // online, offline
            'note' => $this->string(255), // this field saves staff comment, when he proceed this payment information
            'amount' => $this->float(),
            'fee' => $this->float(),
            'total_amount' => $this->float(),
            'currency' => $this->string(50),
            'kingcoin' => $this->float(),
            'exchange_rate' => $this->float(),
            'evidence' => $this->string(255),
            'user_id' => $this->integer(), // ref to User table: this payment used for which user
            'status' => $this->smallInteger()->notNull()->defaultValue(5), // 10 for approved, 5 for pending
            'object_name' => $this->string(255)->notNull(),
            'object_key' => $this->integer()->notNull(),
            'confirmed_by' => $this->integer(),
            'confirmed_at' => $this->dateTime(),
            'created_at' => $this->dateTime(),
            'created_by' => $this->integer(),
            'updated_at' => $this->dateTime(),
            'updated_by' => $this->integer(),
        ], $tableOptions);

        // creates index for column `object_name`
        $this->createIndex(
            'idx-object-name',
            '{{%payment_commitment}}',
            'object_name'
        );
        // creates index for column `object_key`
        $this->createIndex(
            'idx-object-key',
            '{{%payment_commitment}}',
            'object_key'
        );
    }

    public function down()
    {
        echo "m200613_083530_affiliate cannot be reverted.\n";

        $this->dropTable('{{%payment_reality}}');
        $this->dropTable('{{%payment_commitment}}');
        $this->dropIndex('idx-object-name', '{{%payment_reality}}');
        $this->dropIndex('idx-object-key', '{{%payment_reality}}');
        $this->dropIndex('idx-object-name', '{{%payment_commitment}}');
        $this->dropIndex('idx-object-key', '{{%payment_commitment}}');
        return false;
    }
}
