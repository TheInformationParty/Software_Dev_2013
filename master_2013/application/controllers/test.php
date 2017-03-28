<?php

class Test_Controller extends Base_Controller {

	public $restful = true;

	
	public function get_testfun()
	{
		$stances = Stance::filtered_stances(array(),array(),array('Platform'));
		return View::make('test.testview')
			->with('title','TestZone')
			->with('subtitle','Place your testcode in this view to see how it runs!')
			->with('stances', $stances);
	}

}