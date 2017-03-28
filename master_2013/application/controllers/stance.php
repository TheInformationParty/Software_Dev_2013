<?php

class Stance_Controller extends Base_Controller {

    public $restful = true;

    public function get_add(){
        //return the view that users use to submit a stance
        return View::make('stance.add')->with('title', 'Submit a Stance')->with('subtitle', 'Try to make your views official party Stances');;
    }

    public function get_view($id = null){
        //TODO return the view.
        $stance = Stance::find($id);
        return View::make('stance.view')
            ->with('title', 'Stance')
            ->with('subtitle', "This is a Stance!")
            ->with('stance', $stance);
    }

    public function get_your_stances(){
        $stances = Auth::user()->stances()->paginate(3);
        return View::make('stance.collection')
            ->with('stances',$stances)->with('title', 'Your Stances')->with('subtitle', 'Click for details!');
    }

    public function get_all_stances(){
        $stances = Stance::paginate(3);
        return View::make('stance.collection')
            ->with('stances',$stances)->with('title', 'All Stances')->with('subtitle', 'Click for details!');
    }

    public function post_add(){
        //add the stance to the database if validation passes.
        //create the stance
        $stance = new Stance();
        $stance->name = Input::get('name');
        $stance->type = Input::get('type');
        $stance->region_id = Input::get('region');
        $stance->body = Input::get('body');

        //parse the body for links, throw error for bad syntax
        try {
            $links = MarkDown::parse_for_links($stance->body);
        } catch (Exception $e) {
            return Redirect::to_route('add_stance')->with('error',$e->getMessage())->with_input();    
        }

        $validation = $stance->validate();
        if ($validation->passes()){
            //try to parse the tags input
            $tags = array();
            if(strcmp(Input::get('tags'),"")){
                try{
                    $tags = StanceTag::parse(Input::get('tags'));
                } catch (Exception $e) {
                    //redirect back to add stance page with error
                    return Redirect::to_route('add_stance')->with('error',$e->getMessage())->with_input();
                }

                //make sure there are 3 or less tags.
                if(count($tags)>3){
                    return Redirect::to_route('add_stance')->with('error',"Too many tags! 3 or less, please. :]")->with_input();
                }
            }

            //add the stance
            $user = Auth::user();
            $user->stances()->insert($stance);

            //create the stanceTags, add them to the stance
            foreach($tags as $tag){
                $stanceTag = new StanceTag();
                $stanceTag->tag = $tag->tag;
                $stanceTag->stance_id = $stance->id;
                $stanceTag->save();
            }

            //add the links to the stance
            foreach($links as $link){
                $stance->links()->attach($link->id);
            }

            //add user's Endorsement
            $endorse = new StanceVote();
            $endorse->user_id = $user->id;
            $endorse->isendorse = true;
            $endorse->isprotest = false;
            $stance->stancevotes()->insert($endorse);

            //send them to the newly created Stance
            return Redirect::to_route('stance_view', $stance->id);
        } else {
            return Redirect::to_route('add_stance')->with_errors($validation)->with_input();
        }
    }

    public function post_endorse(){
        //Make sure this stance is in this user's regions
        $stance = Stance::find(Input::get('stance_id'));
        $user = Auth::user();
        if(!($stance->can_vote($user))){
            return Redirect::to_route('stance_view', $stance->id)->with('error', "Can't vote on this Stance: Invalid Stance id");
        } else {
            //get this user's stancevotes for the input stance
            $vote = $user->stancevotes()->where('stancevotes.stance_id','=',Input::get('stance_id'))->first();

            if(is_null($vote)){
                //this user have never voted on this stance, add the data
                $vote = new StanceVote();
                $vote->user_id = $user->id;
                $vote->stance_id = $stance->id;
                $vote->isendorse = 1;
                $vote->isprotest = 0;
                $validation = $vote->validate();
                if ($validation->passes()){
                    $vote->save();
                }
            } else {
                //this user has voted on this stance, update the data
                $vote->isendorse = 1;
                $vote->isprotest = 0;
                $validation = $vote->validate();
                if ($validation->passes()){
                    $vote->save();
                }
            }

            //send them back to the stance page
            return Redirect::to_route('stance_view', $stance->id);
        }
    }

    public function post_protest(){
        //Make sure this stance is in this user's regions
        $stance = Stance::find(Input::get('stance_id'));
        $user = Auth::user();
        if(!($stance->can_vote($user))){
            return Redirect::to_route('stance_view', $stance->id)->with('error', "Can't vote on this Stance: Invalid Stance id");
        } else {
            //get this user's stancevotes for the input stance
            $vote = $user->stancevotes()->where('stancevotes.stance_id','=',Input::get('stance_id'))->first();

            if(is_null($vote)){
                //this user have never voted on this stance, add the data
                $vote = new StanceVote();
                $vote->user_id = $user->id;
                $vote->stance_id = $stance->id;
                $vote->isendorse = 0;
                $vote->isprotest = 1;
                $validation = $vote->validate();
                if ($validation->passes()){
                    $vote->save();
                }
            } else {
                //this user has voted on this stance, update the data
                $vote->isendorse = 0;
                $vote->isprotest = 1;
                $validation = $vote->validate();
                if ($validation->passes()){
                    $vote->save();
                }
            }

            //send them back to the stance page
            return Redirect::to_route('stance_view', $stance->id);
        }
    }

    public function post_filter(){
        //get the input
        $type = Input::get('type');
        $region_ids = Input::get('region-ids');
        $stancetag_ids = Input::get('stancetag-ids');

        $types = array();
        $regions = array();
        $stance_tags = array();
        $users = array();

        //convert type to array
        if(!(strcmp($type, "All"))){
            //do nothing
        } else {
            array_push($types, $type);
        }
        //parse regions from region_ids string
        $region_ids = explode(",", $region_ids);
        foreach ($region_ids as $region_id) {
            //region_id is a string
            array_push($regions, Region::find($region_id));
        }
        //parse tags from
        $stancetag_ids = explode(",", $stancetag_ids);
        foreach ($stancetag_ids as $stancetag_id) {
            //stancetag_id is a string
            array_push($stance_tags, StanceTag::find($stancetag_id));
        }

        // return serialize($types).serialize($regions).serialize($stance_tags);
        //TODO: Implement ability to filter by user

        //call Tim's filter
        $filtered_stances = Stance::filtered_stances($regions, $users, $types, $stance_tags);

        //return stance collection view with fitlered stances
        return View::make('stance.collection')
            ->with('stances',$filtered_stances)->with('title', 'Filtered Stances')->with('subtitle', count($filtered_stances).' Stances found with that criteria');
    }
}