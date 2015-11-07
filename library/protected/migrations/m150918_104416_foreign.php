<?php

class m150918_104416_foreign extends CDbMigration
{
	public function up()
	{
		$this->addForeignKey('statusFK', 'item', 'status_id', 'status', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		echo "m150918_104416_foreign does not support migration down.\n";
		return false;
	}

	/*
	// Use safeUp/safeDown to do migration with transaction
	public function safeUp()
	{
	}

	public function safeDown()
	{
	}
	*/
}