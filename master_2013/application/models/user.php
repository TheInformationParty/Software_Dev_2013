<?php

class User extends BaseModel {

    public function rules() {
        //if this user already exists, don't use required
        if($this->exists()){
            return array(
                'username'=>'required|alpha_dash|min:5|max:25',
                'email'=>'required|email',
                'password'=>'required|between:4,64',
                'reputation'=>'integer|min:0',
                'firstName'=>'required|min:2|max:50|alpha',
                'lastName'=>'required|min:2|max:50|alpha',
                'description'=>'min:2|max:1000',
                'gender'=>'between:4,64|alpha',
                'birthdate'=>'after:1897-04-18', //this is the day before the oldest living person was born as of time of writing this code
                'race'=>'min:2|max:50',
                'lastLogin'=>'after:2013-04-21', //the day I wrote this code. No one could ever have a lastLogin before I wrote this code
                'isActive'=>'in:0,1'
            );
        } else {
            return array(
                'username'=>'required|unique:users|alpha_dash|min:5|max:25',
                'email'=>'required|unique:users|email',
                'password'=>'required|between:4,64',
                'reputation'=>'integer|min:0',
                'firstName'=>'required|min:2|max:50|alpha',
                'lastName'=>'required|min:2|max:50|alpha',
                'description'=>'min:2|max:1000',
                'gender'=>'between:4,64|alpha',
                'birthdate'=>'after:1897-04-18', //this is the day before the oldest living person was born as of time of writing this code
                'race'=>'min:2|max:50',
                'lastLogin'=>'after:2013-04-21', //the day I wrote this code. No one could ever have a lastLogin before I wrote this code
                'isActive'=>'in:0,1'
            );
        }
    }

    public static $timestamps = true;

	public function links()
        {
            return $this->has_many_and_belongs_to('Link');
        }

    public function postalcode()
        {
            return $this->belongs_to('postalcode');
        }        

	public function roles()
        {
            return $this->has_many_and_belongs_to('Role');
        }

    public function regions()
        {
            return $this->has_many_and_belongs_to('Region');
        }

    public function stances()
        {
            return $this->has_many('Stance');
        }

    public function comments()
        {
            return $this->has_many('Comment');
        }

    public function followers()
        {
        	$followers = DB::table('followers')
        		->left_join('users', 'users.id', '=', 'followers.follower_id')
        		->where('followers.following_id', '=', $this->id)
        		->where("followers.isCurrent", "=", 1)
        		->get();
        	return $followers;
        }

    public function following()
        {
        	//should return all the users that this user is following
        	$following = DB::table('followers')
        		->left_join('users', 'users.id', '=', 'followers.following_id')
        		->where('followers.follower_id', '=', $this->id)
        		->where("followers.isCurrent", "=", 1)
        		->get();
        	return $following;
        }

    public function appealVotes()
        {
            return $this->has_many('AppealVote');
        }

    public function upVotes()
        {
            return $this->has_many('UpVote');
        }

    public function stancevotes()
        {
            return $this->has_many('StanceVote');
        }

    public function suspensions()
        {
            return $this->has_many('Suspension');
        }

    public function stanceReports()
        {
            return $this->has_many('StanceReport');
        }

    public function commentReports()
        {
            return $this->has_many('CommentReport');
        }

    public function suspensions_moderated()
        {
            return $this->has_many('Suspension');
        }

    public function reputationChanges()
    {
        return $this->has_many('ReputationChange');
    }

    public function to_array()
    {
        return array(
            'username'=>$this->username,
            'email'=>$this->email,
            'password'=>$this->password,
            'reputation'=>$this->reputation,
            'postalcode'=>$this->postalcode,
            'firstName'=>$this->firstname,
            'lastName'=>$this->lastname,
            'description'=>$this->description,
            'gender'=>$this->gender,
            'birthdate'=>$this->birthdate, //this is the day before the oldest living person was born as of time of writing this code
            'race'=>$this->race,
            'lastLogin'=>$this->lastlogin, //the day I wrote this code. No one could ever have a lastLogin before I wrote this code
            'isActive'=>$this->isactive
        );
    }

    public function exists()
    {
        return !(is_null(User::where('username','=',$this->username)->first()));
    }

    public function description_as_html(){
        //get the body
        $description = $this->description;
        //replace the Markdown links in the body with html links
        $description = MarkDown::replace_links($description);
        return $description;
    }

    public function is_endorsing($stance){
        return !(is_null($this->stancevotes()
        ->where('stance_id','=', $stance->id)
        ->where('isendorse', '=', 1)
        ->where('isprotest', '=', 0)
        ->first()));
    }

    public function is_protesting($stance){
        return !(is_null($this->stancevotes()
        ->where('stance_id','=', $stance->id)
        ->where('isendorse', '=', 0)
        ->where('isprotest', '=', 1)
        ->first()));
    }

    public function can_endorse($stance){
        return (!($this->is_endorsing($stance))) AND $stance->can_vote($this);
    }

    public function can_protest($stance){
        return (!($this->is_protesting($stance))) AND $stance->can_vote($this);
    }

    public function has_upvoted($comment){
        $match_upvote = $this->upvotes()->where('upvotes.comment_id', '=', $comment->id)->first();
        return !(is_null($match_upvote));      
    }
}