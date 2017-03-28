<?php
class Region extends BaseModel {

    public function rules() {
        return array(
			'shortname'=>'required',
			'longname'=>'required',
			'scope_id'=>'required'
        );
    }	

    public static $timestamps = false;

	public function users()
    {
    	return $this->has_many_and_belongs_to('User');
    }

	public function postalcodes()
	{
		return $this->has_many_and_belongs_to('PostalCode');
	}

	public function scope()
	{
		return $this->belongs_to('Scope');
	}

	public function parent_region()
	{
		return $this->belongs_to('Region', 'parentregion_id');
	}

	public function stances()
	{
		return $this->has_many('Stance');
	}

    public function to_array()
    {
        return array(
            'shortname'=>$this->shortname,
            'longname'=>$this->longname,
            'scope_id'=>$this->scope_id,
            'parentregion_id'=>$this->parentregion_id
        );
    }

    /**
     * Adds new regions associated with $zip. Note, since we've never
     * seen this zip before, we have to make an API call to the 
     * google geocode API
     * @param string $zip
     * @return boolean success/failure
     * @throws Exception If the postal code is not new
     * @throws Exception If the status from the geocode call isn't OK
     * @throws Exception If a region returned from the geocode call fails validation
     */
    public static function add_regions_from_postalcode($postalcode)
    {    	
    	//first, make sure there aren't regions with this zip. If there are, 
        if(!(isset($postalcode)))
        {
        	//this is not a new zip, no reason to add regions because the regions are automatically added when a new postal code is added. :]
        	throw new Exception('You passed in a null postalcode! How can I add regions with that?');
        } else 
        {
        	//this is a new zip, so do work!
        	//first, make the geocode API call
        	$zip = $postalcode->postalcode;
        	$zip = urlencode($zip);
	        $request = file_get_contents("http://maps.google.com/maps/api/geocode/json?address=".$zip."&sensor=false");
	        $region_data = json_decode($request, true);
	        //make sure the status is okay
	        if(!(strcmp($region_data['status'],"OK")))
	        {
	        	//status was okay
	        	//get the postalcode
	        	$scopeTypes = Scope::types();
	        	$addressComponents = $region_data['results'][0]['address_components'];
	        	$addressComponents = array_reverse($addressComponents);
	        	$parent = null; //this will be storing the parent region for the for loop to check is new

	        	for($i = 0; $i < count($addressComponents); $i++){
	        		
	        		$addressComponent = $addressComponents[$i];

	        		$hasScope = false;
	        		$type = '';
	        		//warning, iterates through all.
	        		foreach($scopeTypes as $scopeType)
	        		{
	        			foreach($addressComponent['types'] as $addressComponentType)
	        			{
	        				if(!(strcmp($scopeType,$addressComponentType)))
	        				{
	        					$hasScope = true;
	        					$type = $scopeType;
	        				}
	        			}
	        		}

	        		if($hasScope)
	        		{
	    				//we now need to add the region, since we know it's of the right type
	    				$scope = Scope::get_from_type($type);
	    				if(Region::is_new($addressComponent['short_name'], $addressComponent['long_name'], $scope, $parent))
	    				{
	    					$region = new Region();
		    				$region->shortname = $addressComponent['short_name'];
		    				$region->longname = $addressComponent['long_name'];
		    				$region->scope_id = $scope->id;
		    				if(!(is_null($parent)))
		    				{
		    					$region->parentregion_id = $parent->id;
		    				} else {
		    					$region->parentregion_id = null;
		    				}
	    					$validation = $region->validate();
				            if ($validation->passes())
				            {
				                $region->save();
				            } else {
				            	//get the errors from the Region validation
				            	$errorString = "";
				            	foreach($validation->errors->all() as $error)
				            	{
				            		$errorString .= $error."<br/>";
				            	}
				            	throw new Exception($errorString);
				            }
	    				} else {
	    					//region is not new, so look it up
	    					$region = Region::get($addressComponent['short_name'], $addressComponent['long_name'], $scope, $parent);
	    					if(is_null($region))
	    					{
	    						throw new Exception("Trying to get a region that does not exist");
	    					}
	    				}
	    				$parent = $region;//for next time through the loop
	    				//attach this region to the postal code
			            $postalcode->regions()->attach($region->id);
	    			}
	    		}	    			
	        } else 
	        {
	        	//status not okay, bad zip; throw error
	        	throw new Exception('The API call failed. Likely a bad zip.');
	        }
        }
    }

    /**
     * Determines if this is a new region
     * @param  string  $shortname
     * @param  string  $longname 
     * @param  PostalCode  $postalcode    
     * @param  Scope  $scope      
     * @return boolean           
     */
    public static function is_new($shortname, $longname, $scope, $parent)
    {
    	if(is_null($parent))
    	{
    	   	$compareRegion = DB::table('regions')->where('longname',"=",$longname)
    	   		->where('shortname',"=",$shortname)
    			->where('scope_id',"=",$scope->id)
    			->where_null('parentregion_id')
    			->first();  	
   		} else {
    	   	$compareRegion = DB::table('regions')->where('longname',"=",$longname)
    	   		->where('shortname',"=",$shortname)
    			->where('scope_id',"=",$scope->id)
    			->where('parentregion_id',"=",$parent->id)
    			->first();
    	}
    	return is_null($compareRegion);
    }

    public static function get($shortname, $longname, $scope, $parent)
    {
    	if(is_null($parent)){
    		$region = Region::where('shortname','=', $shortname)
			->where('longname','=', $longname)
			->where('scope_id','=', $scope->id)
			->first();	
    	} else {
    		$region = Region::where('shortname','=', $shortname)
			->where('longname','=', $longname)
			->where('scope_id','=', $scope->id)
			->where('parentregion_id','=', $parent->id)
			->first();
    	}
		
		//find the region with the right scope
		if(is_null($region)){
			throw new Exception("You're trying to get a Region that doesn't exist!");
		} else {
			return $region;
		}
	}

	public static function search($input){
		//TODO. Implement keyword search

		// return $input;
		$regions = Region::where('longname', 'LIKE', '%'.$input.'%')
			->or_where('shortname', 'LIKE', '%'.$input.'%')
			->get();

		//TODO add parent regions to regions which are doubles so they can be differentiated

		return $regions;
	}
}