<?php

class Scope extends BaseModel {

    public function rules() {
        return array(
			'type'=>'required'
            );
    }

    public static $timestamps = false;
    
	public function regions()
		{
			return $this->has_many('Region');
		}

    public function to_array()
    {
        return array(
            'type'=>$this->type
            );
    }

    public static function types(){
        $scopes = Scope::all();
        $scopeTypes = array();
        foreach($scopes as $scope){
            array_push($scopeTypes,$scope->type);
        }
        return $scopeTypes;
    }

    public static function get_id_from_type($type){
        $scope = Scope::where("type","=",$type)->first();
        if(is_null($scope)){
            throw new Exception('No Scope of that type exists');
        } else {
            return $scope->id;
        }
    }

    public static function get_from_type($type){
        return Scope::where("type","=",$type)->first();
    }
	
}