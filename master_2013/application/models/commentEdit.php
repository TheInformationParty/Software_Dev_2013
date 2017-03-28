<?php

class CommentEdit extends BaseModel {

    public function rules() {
        return array(
			'body'=>'required|between:2,1000',
			'isendorse'=>'required|in:0,1',
			'isprotest'=>'required|in:0,1'
            );
    }

	public static $timestamps = true;

	public function comment()
        {
            return $this->belongs_to('edit');
        }

    public function to_array()
    {
        return array(
            'body' => $this->body
            'isendorse'=>$this->isendorse,  
            'isprotest'=>$this->isprotest                        
            );
    }

}