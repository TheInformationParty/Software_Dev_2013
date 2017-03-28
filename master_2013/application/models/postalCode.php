    <?php

class PostalCode extends BaseModel {

    public function rules() {
        return array(
			'postalcode'=>'required|between:5,5',
        );
    }

    public static $timestamps = true;	

	public function users()
	{
		return $this->has_many('User');//not sure if correct - Tim | Pretty sure it's right - Scott
	}

	public function regions()
	{
		return $this->has_many_and_belongs_to('Region');
	}	
     
    public function to_array()
    {
        return array(
            'postalcode'=>$this->postalcode           
        );
    }

    /**
     * Returns an instance of the PostalCode that is craeted
     * @param string $zip
     * @return PostalCode
     */
    public static function add($zip)
    {
        //make sure this zip doesn't exist in the database
        if(!PostalCode::exists($zip)){
            //insert this zip into the postalcode table
            $postalcode = new PostalCode();
            $postalcode->postalcode = $zip;
            //try to validate this postalcode
            $validation = $postalcode->validate();
            if ($validation->passes()){
                $postalcode->save();
            } else {
                //get the errors from the postalcode validation
                $errorString = "";
                foreach($validation->errors->all() as $error){
                    $errorString .= $error."<br/>";
                }
                throw new Exception($errorString);
            }

            //add the regions that belong to this new zip            
            try {
                Region::add_regions_from_postalcode($postalcode);
            } catch (Exception $e) {
                throw $e;
            }

            return $postalcode;
        } else {
            //this postalcode already exists, gtfo
            throw new Exception('That\'s not a new ZIP!');
        }
    }

    public static function exists($zip){
        return !(is_null(PostalCode::where('postalcode','=',$zip)->first()));
    }

    public static function get_from_zip($zip){
        $postalcode = PostalCode::where("postalcode","=",$zip)->first();
        if(is_null($postalcode)){
            return null;
        }
        return $postalcode;
    }
}