<?php

class m150820_084503_table extends CDbMigration
{
	public function up()
	{
		$this->createTable('files', array(
			'id'=>'pk',
			'title'=>'varchar(30) NOT NULL',
			'file'=>'blob',
			'status'=> 'tinyint(1) default 1',
			'author_id'=>'int'
		));

		$this->addForeignKey('author', 'files', 'author_id', 'user', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		echo "m150820_084503_table does not support migration down.\n";
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