<?php

class m150811_120715_updateTimes extends CDbMigration
{
	public function up()
	{
		$this->addColumn('post', 'create_time', 'datetime');
		$this->addColumn('post', 'update_time', 'datetime');
	}

	public function down()
	{
		$this->dropColumn('post', 'update_time');
		$this->dropColumn('post', 'create_time');
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