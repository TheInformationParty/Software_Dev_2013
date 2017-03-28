<?php

class Change_Order_Of_Relation_Tables {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::drop('user_role');

		Schema::create('role_user', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')
				->unsigned();
			$table->integer('role_id')
				->unsigned();
			
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->on_delete('restrict')
				->on_update('cascade');
				
			$table->foreign('role_id')
				->references('id')
				->on('roles')
				->on_delete('restrict')
				->on_update('cascade');
		});

		Schema::drop('user_region');

		Schema::create('region_user', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')
				->unsigned();
			$table->integer('region_id')
				->unsigned();
			
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
		});

		Schema::drop('stance_link');

		Schema::create('link_stance', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('stance_id')
				->unsigned();
			$table->integer('link_id')
				->unsigned();
			
			$table->foreign('stance_id')
				->references('id')
				->on('stances')
				->on_delete('restrict')
				->on_update('cascade');
				
			$table->foreign('link_id')
				->references('id')
				->on('links')
				->on_delete('restrict')
				->on_update('cascade');
		});

		Schema::drop('user_link');

		Schema::create('link_user', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')
				->unsigned();
			$table->integer('link_id')
				->unsigned();
			
			$table->foreign('user_id')
				->references('id')
				->on('users')
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
		Schema::drop('role_user');

		Schema::create('user_role', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')
				->unsigned();
			$table->integer('role_id')
				->unsigned();
			
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->on_delete('restrict')
				->on_update('cascade');
				
			$table->foreign('role_id')
				->references('id')
				->on('roles')
				->on_delete('restrict')
				->on_update('cascade');
		});

		Schema::drop('region_user');

		Schema::create('user_region', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')
				->unsigned();
			$table->integer('region_id')
				->unsigned();
			
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
		});

		Schema::drop('link_stance');

		Schema::create('stance_link', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('stance_id')
				->unsigned();
			$table->integer('link_id')
				->unsigned();
			
			$table->foreign('stance_id')
				->references('id')
				->on('stances')
				->on_delete('restrict')
				->on_update('cascade');
				
			$table->foreign('link_id')
				->references('id')
				->on('links')
				->on_delete('restrict')
				->on_update('cascade');
		});

		Schema::drop('link_user');

		Schema::create('user_link', function($table){
			$table->engine = 'InnoDB';

			$table->increments('id');
			$table->integer('user_id')
				->unsigned();
			$table->integer('link_id')
				->unsigned();
			
			$table->foreign('user_id')
				->references('id')
				->on('users')
				->on_delete('restrict')
				->on_update('cascade');
				
			$table->foreign('link_id')
				->references('id')
				->on('links')
				->on_delete('restrict')
				->on_update('cascade');
		});
	}

}