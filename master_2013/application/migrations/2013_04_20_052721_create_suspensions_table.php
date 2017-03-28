<?php

class Create_Suspensions_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('suspensions', function($table){
			$table->engine = 'InnoDB';
			
			$table->increments('id');
			$table->date('startDate');
			$table->date('endDate')->nullable();
			$table->integer('user_id')->unsigned();
			$table->integer('moderator_id')->unsigned();
			$table->string('note');
			$table->timestamps();

			$table->foreign('user_id')
				->references('id')
				->on('users')
				->on_delete('restrict')
				->on_update('cascade');
				
			$table->foreign('moderator_id')
				->references('id')
				->on('users')
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
		Schema::drop('suspensions');
	}

}