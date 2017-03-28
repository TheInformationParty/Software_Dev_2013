<?php

class Create_Region_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('regions', function($table){
			$table->engine = 'InnoDB'; //Need this for foreign keys to work

			$table->increments('id');
			$table->string('name');
			$table->integer('scope_id')
				->unsigned(); //unsigned because this is a column that corresponds to an id column that's an increment (which is unsigned)
			$table->integer('parentregion_id')
				->unsigned();

			$table->foreign('scope_id')
				->references('id')
				->on('scopes')
				->on_delete('restrict')
				->on_update('cascade'); //this is the correct way to setup foreign keys in Laravel
			$table->foreign('parentregion_id')
				->references('id')
				->on('regions')
				->on_delete('restrict')
				->on_update('cascade'); //this is the correct way to setup foreign keys in Laravel
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('regions');
	}

}