<?php

class Create_Stances_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('stances', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')
				->unsigned();
			$table->string('name');
			$table->string('body');
			$table->string('type');
			$table->integer('region_id')
				->unsigned();
			$table->integer('parentStance_id')
				->unsigned()->nullable();
			$table->timestamps();

			$table->foreign('user_id')
				->references('id')
				->on('users')
				->on_delete('restrict')
				->on_update('cascade');
			$table->foreign('region_id')
				->references('id')
				->on('regions')
				->on_delete('restrict')
				->on_update('cascade');
			$table->foreign('parentStance_id')
				->references('id')
				->on('stances')
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
		Schema::drop('stances');
	}

}