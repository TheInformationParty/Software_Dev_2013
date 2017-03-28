<?php

class Add_Scopes {

	/**
	 * Make changes to the database.
	 *
	 * @return void
	 */
	public function up()
	{
		DB::table('scopes')->insert(
			array(
				array('type'=>'locality'),
				array('type'=>'administrative_area_level_2'),
				array('type'=>'administrative_area_level_1'),
				array('type'=>'country')
			)
		);
	}

	/**
	 * Revert the changes to the database.
	 *
	 * @return void
	 */
	public function down()
	{
		DB::table('scopes')->delete();
	}

}