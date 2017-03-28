<?php

class Remove_Region_Hierarchy {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('regions', function($table)
		{
		    $table->drop_foreign('regions_parentregion_id_foreign');
		    $table->drop_column('parentregion_id');
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
		    $table->integer('parentregion_id')
				->unsigned();
			$table->foreign('parentregion_id')
				->references('id')
				->on('regions')
				->on_delete('restrict')
				->on_update('cascade');
		});
	}

}