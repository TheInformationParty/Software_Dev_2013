<?php

class Create_User_Link_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('user_link', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')
				->unsigned();
			$table->integer('link_id')
				->unsigned();
			
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->on_delete('restrict')
				->on_update('cascade');
				
			$table->foreign('link_id')
				->references('id')
				->on('links')
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
		Schema::drop('user_link');
	}

}