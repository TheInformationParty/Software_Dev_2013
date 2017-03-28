<?php

class Postalcode_Region {

		/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('postalcode_region', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('region_id')
				->unsigned();
			$table->integer('postalcode_id')
				->unsigned();
				
			$table->foreign('region_id')
				->references('id')
				->on('regions')
				->on_delete('restrict')
				->on_update('cascade');
					
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
		Schema::drop('postalcode_region');
	}


}