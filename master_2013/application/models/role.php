<?php

class Role extends BaseModel {

    public function rules() {
        return array(
			'type'=>'required'
            );
    }
    
	public function users()
	    {
	    	return $this->has_many_and_belongs_to('User');
	    }

    public function to_array()
    {
        return array(
            'type'=>$this->type
            );
    }

}