<?php

class UpVote extends BaseModel {

    public function rules() {
        return array();
    }

	public static $timestamps = true;
    
    public function user()
        {
            return $this->belongs_to('User');
        }

    public function comment()
        {
            return $this->belongs_to('Comment');
        }

    public function to_array()
    {
        return array();
    }
        

}