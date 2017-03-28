<?php

class AppealVote extends BaseModel {

    public function rules() {
        return array(
			'isendorse'=>'required|in:0,1',
			'isprotest'=>'required|in:0,1'
            );
    }	

	public static $timestamps = true;

	public function user()
		{
			return $this->belongs_to('User');
		}

    public function to_array()
    {
        return array(
            'isendorse'=>$this->isendorse,  
            'isprotest'=>$this->isprotest                        
            );
    }		
}