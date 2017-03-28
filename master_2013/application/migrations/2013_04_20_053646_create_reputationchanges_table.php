<?php

class Create_Reputationchanges_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('reputationChanges', function($table) {
			$table->increments('id');
			$table->string('type');
			$table->integer('value');
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
	}

}