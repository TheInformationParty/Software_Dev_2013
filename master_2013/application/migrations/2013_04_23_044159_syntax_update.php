<?php

class Syntax_Update {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('logReputationChanges');

		Schema::create("reputationChanges", function($table) {
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('event');
			$table->integer('value');
			$table->timestamps();

			$table->foreign('user_id')
				->references('id')
				->on('users')
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
		Schema::drop('reputationChanges');

		Schema::create("logReputationChanges", function($table) {
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')->unsigned();
			$table->string('description');
			$table->integer('value');
			$table->timestamps();

			$table->foreign('user_id')
				->references('id')
				->on('users')
				->on_delete('restrict')
				->on_update('cascade');
		});
	}

}