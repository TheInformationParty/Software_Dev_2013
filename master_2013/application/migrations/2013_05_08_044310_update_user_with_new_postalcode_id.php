<?php

class Update_User_With_New_Postalcode_Id {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('users', function($table)
		{
		    $table->drop_column('postalcode');
		    $table->integer('postalcode_id')
				->unsigned();

		    $table->foreign('postalcode_id')
				->references('id')
				->on('postalcodes')
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
		Schema::table('users', function($table)
		{
		    $table->drop_foreign('users_postalcode_id_foreign');
		    $table->drop_column('postalcode_id');

		    $table->string('postalcode');
		});
	}

}