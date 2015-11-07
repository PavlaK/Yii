<?php

use yii\db\Schema;
use yii\db\Migration;

class m150721_130912_init extends Migration
{
    public function safeUp()
    {
        try {
            $this->dropTable('{{%token}}');
        } catch (Exception $e) {
            echo 'Could not drop table "token".';
        }

        $this->createTable('{{%token}}', [
            'user_id' => 'varchar(36) NOT NULL PRIMARY KEY',
            'code' => 'varchar(36) NOT NULL',
            'updated_at' => "datetime NOT NULL",
        ]);
    }

    public function safeDown()
    {
        $this->dropTable('{{%token}}');
    }
}
