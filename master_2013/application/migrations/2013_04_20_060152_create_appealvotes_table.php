<?php

class Create_AppealVotes_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('appealVotes', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')
				->unsigned();
			$table->integer('appeal_id')
				->unsigned();
			$table->boolean('isendorse');
			$table->boolean('isprotest');
			$table->timestamps();

			$table->foreign('user_id')
				->references('id')
				->on('users')
				->on_delete('restrict')
				->on_update('cascade');
			$table->foreign('appeal_id')
				->references('id')
				->on('appeals')
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
		Schema::drop('appealVotes');
	}

}