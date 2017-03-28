<?php

class Tag extends BaseModel {

    public function rules() {
        return array(
            'tag'=>'required|alpha_num|between:2,140'
        );
    }

    public static $timestamps = false;

    public function to_array()
    {
        return array(
            'tag'=>$this->tag
        );
    }

    /**
     * Decodes a string into Tags using space as the delimeter
     * @param  string $input 
     * @return array        a collection of Tag objects. Returns an empty array if something goes wrong
     */
    public static function parse($input){
        $tagNames = explode(" ", $input);
        $tags = array();
        foreach($tagNames as $tagName){
            $tag = new Tag();
            $tag->tag = $tagName;
            $validation = $tag->validate();
            if ($validation->passes()){
                array_push($tags, $tag);
            } else {
                throw new Exception("Invalid Tag! Only letters and numbers, please");
            }
        }
        return $tags;
    }
}