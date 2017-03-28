<?php

class Create_Comments_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comments', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('stance_id')
				->unsigned();
			$table->integer('user_id')
				->unsigned();
			$table->integer('parentComment_id')
				->unsigned()->nullable();
			$table->boolean('isendorse');
			$table->boolean('isprotest');
			$table->timestamps();

			$table->foreign('stance_id')
				->references('id')
				->on('stances')
				->on_delete('restrict')
				->on_update('cascade');
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->on_delete('restrict')
				->on_update('cascade');
			$table->foreign('parentComment_id')
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
		Schema::drop('comments');
	}

}