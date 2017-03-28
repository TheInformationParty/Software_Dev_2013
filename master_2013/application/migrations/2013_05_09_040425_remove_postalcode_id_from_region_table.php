<?php

class Remove_Postalcode_Id_From_Region_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('regions', function($table)
		{
		    $table->drop_foreign('regions_postalcode_id_foreign');
		    $table->drop_column('postalcode_id');
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
		    $table->integer('postalcode_id')
				->unsigned();
			$table->foreign('postalcode_id')
				->references('id')
				->on('postalcodes')
				->on_delete('restrict')
				->on_update('cascade');
		});
	}

}