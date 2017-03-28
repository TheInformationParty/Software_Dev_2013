<?php

class Restructure_Logreputationchange_Tables {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('logReputationChanges');

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

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::create('reputationChanges', function($table) {
			$table->increments('id');
			$table->string('type');
			$table->integer('value');
		});
	}

}