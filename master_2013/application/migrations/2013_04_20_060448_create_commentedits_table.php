<?php

class Create_Commentedits_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('commentEdits', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('comment_id')
				->unsigned();
			$table->string('body');
			$table->boolean('isendorse');
			$table->boolean('isprotest');
			$table->timestamps();

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
		Schema::drop('commentEdits');
	}

}