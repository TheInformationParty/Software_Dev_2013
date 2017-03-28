<?php

class Create_User_Role_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_role', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')
				->unsigned();
			$table->integer('role_id')
				->unsigned();
			
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->on_delete('restrict')
				->on_update('cascade');
				
			$table->foreign('role_id')
				->references('id')
				->on('roles')
				->on_delete('restrict')
				->on_update('cascade');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('user_role');
	}

}