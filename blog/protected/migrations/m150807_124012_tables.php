<?php

class m150807_124012_tables extends CDbMigration
{
	public function up()
	{
		$this->createTable('post', array(
			'id'=>'pk',
			'title'=>'varchar(30) NOT NULL',
			'content'=>'text',
			'status'=> 'tinyint(1) default 1',
			'tags'=> 'text',
			'author_id'=>'int'
		));
		$this->createTable('user', array(
			'id'=>'pk',
			'username'=>'varchar(30) NOT NULL',
			'password'=>'text',
			'salt'=> 'int',
			'email'=> 'varchar(30) NOT NULL',
			'profile'=> 'varchar(225)'
		));
		$this->createTable('comment', array(
			'id'=>'pk',
			'author'=>'varchar(30) NOT NULL',
			'email'=>'varchar(30) NOT NULL',
			'url'=> 'varchar(30)',
			'content'=> 'text',
			'status'=> 'tinyint(1) default 1',
			'post_id'=>'int'
		));
		$this->createTable('tag', array(
			'id'=>'pk',
			'name'=>'varchar(30) NOT NULL',
			'frequency'=>'int NOT NULL'
		));

		$this->createTable('lookup', array(
			'id'=>'pk',
			'name'=>'varchar(30)',
			'code'=>'int NOT NULL',
			'type'=>'varchar(30)',
			'position'=>'int'
		));

		$this->addForeignKey('author_idFK', 'post', 'author_id', 'user', 'id', 'CASCADE', 'CASCADE');
		$this->addForeignKey('post_idFK', 'comment', 'post_id', 'post', 'id', 'CASCADE', 'CASCADE');
	}

	public function down()
	{
		echo "m150807_124012_tables does not support migration down.\n";
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