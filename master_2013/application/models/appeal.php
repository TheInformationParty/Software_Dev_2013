<?php

class Appeal extends BaseModel {

    public function rules() {
        return array();
    }
    
	public static $timestamps = true;

    public function suspension()
        {
            return $this->belongs_to('Suspension');
        }

    public function appealVotes()
        {
            return $this->has_many('AppealVote');
        }    

    public function to_array()
    {
        return array();                       
    }

}