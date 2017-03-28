<?php

class Create_StanceTags_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stanceTags', function($table) {
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('stance_id')->unsigned();
			$table->string('tag');

			$table->foreign('stance_id')
				->references('id')
				->on('stances')
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
		Schema::drop('stanceTags');
	}

}