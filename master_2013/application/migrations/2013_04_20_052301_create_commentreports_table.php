<?php

class Create_Commentreports_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commentReports', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')
				->unsigned();
			$table->integer('comment_id')
				->unsigned();
			$table->timestamps();
			
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->on_delete('restrict')
				->on_update('cascade');
				
			$table->foreign('comment_id')
				->references('id')
				->on('comments')
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
		Schema::drop('commentReports');
	}

}