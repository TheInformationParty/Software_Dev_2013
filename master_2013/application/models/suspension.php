<?php

class Suspension extends BaseModel {

	public function rules() {
		return array(
			'notes'=>'required|min:2|max:1000'
			);
	}

	public static $timestamps = true;

    public function user()
        {
            return $this->belongs_to('User');
        }

	public function moderator()
		{
			return $this->belongs_to('Comment', 'parentComment_id');
		}        

    public function appeal()
        {
            return $this->has_one('Appeal');
        }

    public function to_array()
    {
        return array(
            'notes'=>$this->notes
            );
    }

}