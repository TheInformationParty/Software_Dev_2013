<?php

class Create_Stance_Link_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stance_link', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('stance_id')
				->unsigned();
			$table->integer('link_id')
				->unsigned();
			
			$table->foreign('stance_id')
				->references('id')
				->on('stances')
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
		Schema::drop('stance_link');
	}
	
}