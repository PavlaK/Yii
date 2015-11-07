<?php

class m150811_143620_timeComments extends CDbMigration
{
	public function up()
	{
		$this->addColumn('comment', 'create_time', 'datetime');
	}

	public function down()
	{
		echo "m150811_143620_timeComments does not support migration down.\n";
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