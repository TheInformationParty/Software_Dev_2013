<?php

class Create_Followers_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create("followers", function($table) {
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('follower_id')->unsigned();
			$table->integer('following_id')->unsigned();
			$table->timestamps();

			$table->foreign('follower_id')
				->references('id')
				->on('users')
				->on_delete('restrict')
				->on_update('cascade');
				
			$table->foreign('following_id')
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
		Schema::drop('followers');
	}

}