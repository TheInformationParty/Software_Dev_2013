<?php

class TestUser extends PHPUnit_Framework_TestCase {

	/**
	 * Test that a given condition is met.
	 *
	 * @return void
	 */
	public function testFullUserCreation()
	{
		//try to create a new user from the model.
		$testUser = User::create(array(
			'username' => 'lowe0292',
			'email' => 'me@scottdlowe.com',
			'password' => Hash::make('scottsfakepassword'),
			'reputation' => 0,
			'lastTermsOfService' => "1.0",
			'postalcode' => "48202",
			'firstName'=>'Scott',
			'lastName'=>'Lowe',
			'description'=>"I hope this test works. :]",
			'gender'=>'male',
			'birthdate'=>'1990-05-28',
			'race'=>'Caucasian'
		));

		$this->assertTrue(true);	
	}

}