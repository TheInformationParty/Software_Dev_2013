<?php

class Create_Appeals_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('appeals', function($table){
			$table->engine = 'InnoDB';
			
			$table->increments('id');
			$table->integer('suspension_id')->unsigned();
			$table->string('note');
			$table->timestamps();

			$table->foreign('suspension_id')
				->references('id')
				->on('suspensions')
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
		Schema::drop('appeals');
	}

}