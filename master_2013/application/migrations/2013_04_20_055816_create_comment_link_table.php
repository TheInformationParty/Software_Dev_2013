<?php

class Create_Comment_Link_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('comment_link', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('comment_id')
				->unsigned();
			$table->integer('link_id')
				->unsigned();
			
			$table->foreign('comment_id')
				->references('id')
				->on('comments')
				->on_delete('restrict')
				->on_update('cascade');
				
			$table->foreign('link_id')
				->references('id')
				->on('links')
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
		Schema::drop('comment_link');
	}

}