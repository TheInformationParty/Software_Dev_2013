<?php

class BaseModel extends Eloquent {

	public function validate() 
	{
		return Validator::make($this->to_array(), $this->rules());
	}

}