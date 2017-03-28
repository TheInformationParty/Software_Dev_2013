<?php

class Create_Roles_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('roles', function($table){
			$table->increments('id');
			$table->string('type')->unique();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('roles');
	}

}