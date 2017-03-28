<?php

class Stance extends BaseModel {

    public function rules() {
        return array(
			'name'=>'required|min: 2|max: 140',
			'body'=>'required|min: 2|max: 1400',
			'type'=>'required|in:Platform,Legislation,Candidate'
            );
    }

	public static $timestamps = true;

	public function links()
		{
			return $this->has_many_and_belongs_to('Link');
		}

	public function creator()
	{
		return $this->belongs_to('User', 'user_id');
	}

	public function region()
	{
		return $this->belongs_to('Region');
	}

	public function parent_stance()
		{
			//Note: not sure what this will return if parent_stance is null :]
			return $this->belongs_to('Stance', 'parentStance_id');
		}

	public function children_stances()
		{
			//Note: not sure what this will return if parent_stance is null :]
			return $this->has_many('Stance', 'parentStance_id');
		}		

	public function comments()
		{
			return $this->has_many('Comment');
		}

	public function stanceReports()
		{
			return $this->has_many('stanceReport');
		}

	public function stancevotes()
		{
			return $this->has_many('stancevote');
		}

	public function stanceTags()
        {
            return $this->has_many('StanceTag');
        }		

    public function to_array()
    {
        return array(
            'name'=>$this->name,
            'body'=>$this->body,
            'type'=>$this->type
            );
    }

    public function body_as_html(){
    	//get the body
    	$body = $this->body;
    	//replace the Markdown links in the body with html links
    	$body = MarkDown::replace_links($body);
    	return $body;
    }

    public function count_endorsements(){
    	$endorsements = $this->stancevotes()
    	->where('isendorse', '=', 1)
    	->where('isprotest', '=', 0)
    	->get();
    	return count($endorsements);
    }

    public function count_protests(){
    	$protests = $this->stancevotes()
    	->where('isendorse', '=', 0)
    	->where('isprotest', '=', 1)
    	->get();
    	return count($protests);
    }

    public function can_vote($user){
        //check this user's regions against this stance's region
        $stance_region_id = $this->region->id;
        $match_region = $user->regions()->where('regions.id','=',$stance_region_id)->first();
        return !(is_null($match_region));
	}

    public function sorted_comments(){
        //get all the comments that are not replies
        $sorted_comments = $this->comments()
        ->where_null('parentComment_id')
        ->get();

        //sort the comments

        $sorted_comments = $this->sort_comments($sorted_comments);

        //make new array and fill it with each comment and those comments sorted replies
        $return_array = array();
        foreach($sorted_comments as $sorted_comment){
            $return_array = array_merge($return_array, array($sorted_comment));
            $return_array = array_merge($return_array, $this->sorted_replies($sorted_comment));
        }

        return $return_array;


    }

    public function sorted_replies($comment){
                //get all the comments replies to the comment
        $sorted_comments = Comment::
                where('comments.parentComment_id', '=', $comment->id)
                ->get();
        //sort the comments
        $sorted_comments = $this->sort_comments($sorted_comments);
        //make new array and fill it with each comment and those comments sorted replies
        $return_array = array();
        foreach($sorted_comments as $sorted_comment){
            $return_array = array_merge($return_array, array($sorted_comment));
            $return_array = array_merge($return_array, $this->sorted_replies($sorted_comment));
        }
        return $return_array;
    }


    //algorithm for sorting comments
    public function sort_comments($comments){
        //for now just sorting by who has most upvotes  
        
        if (!function_exists('compare_comments')) {
        //the actual function used to compare two elements.
        // return < 0 if a<b, return 0 if a=b, return > 0 if a>b
            function compare_comments($a, $b){
                //if(strcmp(get_class($a), 'Comment')) throw new Exception(serialize(get_class($a)));
                $up_votes_for_a = count($a->upVotes()->get());
                $up_votes_for_b = count($b->upVotes()->get());
                return $up_votes_for_b - $up_votes_for_a;
            } 
        }

        usort($comments, "compare_comments");
        return $comments;
    }

    //retruns an array of stances that meet the input criteria, returns empty if no filter applied
    /*-----------------------------------------------------UNTESTED!!!!!-----------------------------------------------------*/
    public static function filtered_stances($regions, $users, $types, $stance_tags){
        //this funcition utilized the nested where clause
        //example implementaion at http://laravel.com/docs/database/fluent#nested-where
        $return_array = Stance::
            where(function($query){
                if(!(empty($regions))){
                    $query->where(function($region_query){
                        $region_query->where('stances.region_id', '=', $regions[0]->id);
                        foreach($regions as $region){
                            $region_query->or_where('stances.region_id', '=', $region->id);
                        }
                    });
                }
                if(!(empty($users))){
                    $query->where(function($user_query){
                        $user_query->where('stances.user_id', '=', $users[0]->id);
                        foreach($users as $user){
                            $user_query->or_where('stances.user_id', '=', $user->id);
                        }
                    });
                }
                if(!(empty($types))){
                    $query->where(function($type_query){
                        $type_query->where('stances.type', '=', $types[0]);
                        foreach($types as $type){
                            $type_query->or_where('stances.type', '=', $type);
                        }
                    });
                }
                if(!(empty($stance_tags))){
                    $query->where(function($tags_query){
                        $tags_query->where('stances.type', '=', $types[0]);
                        foreach($types as $type){
                            $tags_query->or_where('stances.type', '=', $type);
                        }
                    });
                }
            })
            // ->get();
            ->paginate(3);

        return $return_array;

    }

}