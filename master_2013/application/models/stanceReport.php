<?php

class StanceReport extends BaseModel {

    public function rules() {
        return array();
    }

	public static $timestamps = true;
    
    public function user()
        {
            return $this->belongs_to('User');
        }

    public function stance()
        {
            return $this->belongs_to('Stance');
        }

    public function stanceReportTags()
        {
            return $this->has_many('StanceReportTag');
        }           

    public function to_array()
    {
        return array(
            );
    }
    
}