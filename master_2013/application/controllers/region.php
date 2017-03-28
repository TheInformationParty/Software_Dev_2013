<?php

class Region_Controller extends Base_Controller {

    public $restful = true;

    public function post_query(){
        if(!(isset($_POST['query']))){
            return Response::json("No query string passed!");
        } else {
            $query = $_POST['query'];
            return Response::json(json_encode(Region::search($query)));
        }
    }

}