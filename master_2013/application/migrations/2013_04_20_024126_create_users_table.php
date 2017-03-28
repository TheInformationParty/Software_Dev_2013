<?php

class Create_Users_Table {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function($table){
			$table->increments('id');
			$table->string('username')->unique();
			$table->string('email')->unique();
			$table->string('password');
			$table->integer('reputation')->default(0);
			$table->string('lastTermsOfService');
			$table->string('postalcode');
			$table->string('firstName');
			$table->string('lastName');
			$table->string('description')->nullable();
			$table->string('gender')->nullable();
			$table->date('birthdate')->nullable();
			$table->string('race')->nullable();
			$table->date('lastLogin')->nullable();
			$table->boolean('isActive')->default(1);
			$table->timestamps();
		});
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('users');
	}

}