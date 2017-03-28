<?php

class Link extends BaseModel {

    public function rules() {
        return array(
			'url'=>'required|between:2,2000',
			'name'=>'between:2,70'
            );
    }	

    public static $timestamps = false;

	public function users()
		{
			return $this->has_many_and_belongs_to('User');
		}
     
	public function comments()
		{
			return $this->has_many_and_belongs_to('Comment');
		}

	public function stances()
		{
			return $this->has_many_and_belongs_to('Stance');
		}

    public function to_array()
    {
        return array(
			'url'=>$this->url,
        	'name'=>$this->name            
        );
    }

}