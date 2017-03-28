<?php

class StanceTag extends Tag {
    
	public function stance()
    {
        return $this->belongs_to('Stance');
    }

    public static function search($input){
        //TODO. Implement keyword search

        // return $input;
        $stancetags = StanceTag::where('tag', 'LIKE', '%'.$input.'%')
            ->group_by('tag')	//the group_by makes sure tags are not repeated
            ->get();

        return $stancetags;
    }
}