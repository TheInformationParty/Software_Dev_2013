<?php

class Tag_Controller extends Base_Controller {

    public $restful = true;

    public function post_query_stancetags(){
        if(!(isset($_POST['query']))){
            throw new Exception("No query string passed!");
        } else {
            $query = $_POST['query'];
            return Response::json(json_encode(StanceTag::search($query)));
        }
    }

}