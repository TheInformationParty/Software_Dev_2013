<?php

class Add_Body_To_Comments {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('comments', function($table)
		{
			$table->string('body');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('comments', function($table)
		{
			$table->drop_column('parentregion_id');
		});
	}

}