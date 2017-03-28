<?php

class Add_Long_And_Short_Names_To_Region {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('regions', function($table)
		{
		    $table->drop_column('name');
		    $table->string('longName');
		    $table->string('shortName');
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('regions', function($table)
		{
		    $table->drop_column('longName');
		    $table->drop_column('shortName');
		});
	}

}