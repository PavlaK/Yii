<?php

use yii\db\Schema;
use yii\db\Migration;

class m150807_080951_createOrders extends Migration
{
    public function safeUp()
    {
        try {
            $this->dropTable('{{%orders}}');
        } catch (Exception $e) {
            echo 'Could not drop table "orders".';
        }

        $this->createTable('{{%orders}}', [
            'id' => 'pk',
            'user_id' => 'varchar(36) NOT NULL',
            'amout' => 'decimal(20,4) NOT NULL',
            'coins' => 'varchar(36) NOT NULL',
            'status' => 'int(11) NOT NULL',
            'create_at' => "datetime NOT NULL",
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%orders}}');
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }
    
    public function safeDown()
    {
    }
    */
}
