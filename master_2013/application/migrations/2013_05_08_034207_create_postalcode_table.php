<?php

class Create_Postalcode_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('postalcodes', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->string('postalcode')->unique();
		});

		Schema::table('regions', function($table)
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
		Schema::table('regions', function($table)
		{
		    $table->drop_foreign('regions_postalcode_id_foreign');
		    $table->drop_column('postalcode_id');

		    $table->string('postalcode');
		});

		Schema::drop('postalcodes');
	}

}