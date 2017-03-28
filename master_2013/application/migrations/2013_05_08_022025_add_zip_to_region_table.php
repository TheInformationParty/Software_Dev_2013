<?php

class Add_Zip_To_Region_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{

		Schema::table('regions', function($table)
		{
			$table->string('postalcode');
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
		    $table->drop_column('postalcode');
		});
	}

}