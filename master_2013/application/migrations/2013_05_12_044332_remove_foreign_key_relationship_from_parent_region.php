<?php

class Remove_Foreign_Key_Relationship_From_Parent_Region {

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
			$table->foreign('parentregion_id')
				->references('id')
				->on('regions')
				->on_delete('restrict')
				->on_update('cascade');
		});
	}

}