<?php

class Comment extends BaseModel {

    public function rules() {
        return array(
			'body'=>'required|between:2,1000',
			'isendorse'=>'required|in:0,1',
			'isprotest'=>'required|in:0,1'
            );
    }
	
	public static $timestamps = true;
	
	public function links()
		{
			return $this->has_many_and_belongs_to('Link');
		}

	public function stance()
		{
			return $this->belongs_to('Stance');
		}

	public function user()
		{
			return $this->belongs_to('User');
		}
    
	public function parent_comment()
		{
			return $this->belongs_to('Comment', 'parentComment_id');
		}

	public function children_comments()
		{
			return $this->has_many('Comment', 'parentComment_id');
		}

	public function upVotes()
		{
			return $this->has_many("UpVote");
		}

	public function commentReports()
        {
            return $this->has_many('CommentReport');
        }

	public function commentEdits()
        {
            return $this->has_many('CommentEdit');
        }

    public function to_array()
    {
        return array(
            'body' => $this->body,
            'isendorse'=>$this->isendorse,  
            'isprotest'=>$this->isprotest                        
            );
    }

    public function body_as_html(){
    	//get the body
    	$body = $this->body;
    	//replace the Markdown links in the body with html links
    	$body = MarkDown::replace_links($body);
    	return $body;
    }

    public function depth(){
    	//get parentcomment until parentcomment_id is null
    	$depth = 0;
    	$root = false;
    	$comment = $this;
    	while(!($root)){
    		if(is_null($comment->parentcomment_id)){
    			$root = true;
    		} else {
    			$depth += 1;
    			$comment = Comment::find($comment->parentcomment_id);
    		}
    	}
    	return $depth;
    }
}