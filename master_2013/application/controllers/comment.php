<?php

class Comment_Controller extends Base_Controller {

    public $restful = true;

    public function get_add($stance_id = null){
        return View::make('comment.add')
            ->with('title',Stance::find($stance_id)->name)
            ->with('subtitle', 'test')
            ->with('stance_id', $stance_id);
    }

    public function get_reply($parent_comment_id = null){
        $parent_comment = Comment::find($parent_comment_id);
        // return $parent_comment->id;
        return View::make('comment.reply')
        ->with('title',"Reply")
        ->with('subtitle', 'to '.$parent_comment->user->username."'s comment")
        ->with('parent_comment_id', $parent_comment_id);
    }

    public function post_add(){
        //get the stance from the hidden input
        $stance_id = Input::get("stance_id");
        
        //try to create the comment object
        $comment = new Comment();
        $comment->stance_id = $stance_id;
        $comment->user_id = Auth::user()->id;
        $comment->body = Input::get("body");
        $comment->isendorse = Input::get('endorsing');
        $comment->isprotest = !(Input::get('endorsing'));

        $validation = $comment->validate();
        if ($validation->passes()){
            //add the comment
            $comment->save();

            //parse the body for links, throw error for bad syntax
            try {
                $links = MarkDown::parse_for_links($comment->body);
            } catch (Exception $e) {
                return Redirect::to_route('add_comment', $stance_id)
                    ->with('error',$e->getMessage())
                    ->with_input();    
            }

            //add the links to the stance
            foreach($links as $link){
                $comment->links()->attach($link->id);
            }

            //TODO: auto add an upvote

            //send them to the stance with the new comment
            return Redirect::to_route('stance_view', $stance_id);
        } else {
            return Redirect::to_route('add_comment', $stance_id)
                ->with_errors($validation)->with_input();
        }
    }

    public function post_reply(){
        //TODO BUGFIX. This function is saving the comment even when validation fails

        //get the stance from the hidden input
        $parent_comment_id = Input::get("parent_comment_id");
        $parent_comment = Comment::find($parent_comment_id);
        $stance_id = $parent_comment->stance->id;

        //try to create the comment object
        $comment = new Comment();
        $comment->stance_id = $stance_id;
        $comment->parentcomment_id = $parent_comment_id;
        $comment->user_id = Auth::user()->id;
        $comment->body = Input::get("body");
        $comment->isendorse = Input::get('endorsing');
        $comment->isprotest = !(Input::get('endorsing'));

        $validation = $comment->validate();
        if ($validation->passes()){
            //add the comment
            $comment->save();

            //parse the body for links, throw error for bad syntax
            try {
                $links = MarkDown::parse_for_links($comment->body);
            } catch (Exception $e) {
                return Redirect::to_route('add_comment', $stance_id)
                    ->with('error',$e->getMessage())
                    ->with_input();    
            }

            //add the links to the stance
            foreach($links as $link){
                $comment->links()->attach($link->id);
            }

            //TODO: auto add an upvote

            //send them to the stance with the new comment
            return Redirect::to_route('stance_view', $stance_id);
        } else {
            return Redirect::to_route('add_comment_reply', $parent_comment_id)
                ->with_errors($validation)
                ->with_input();
        }
    }

    public function post_upvote(){
        $comment_id = Input::get("comment_id");
        $comment = Comment::find($comment_id);
        $user = Auth::user();

        if($user->has_upvoted($comment)){
            //gtfo
            return Redirect::to_route('stance_view', $comment->stance->id)->with('error',"You've already upvoted that comment!");
        } else {
            //  create and save upvote
            $upvote = new UpVote();
            $upvote->user_id = $user->id;
            $upvote->comment_id = $comment->id;

            $validation = $upvote->validate();
            if ($validation->passes()){
                $upvote->save();
                return Redirect::to_route('stance_view', $comment->stance->id);
            } else {
                return Redirect::to_route('add_comment', $comment->stance->id)
                ->with_errors($validation);
            }
        }
    }


}