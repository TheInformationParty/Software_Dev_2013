<?php

class CommentReportTag extends Tag {
    
	public function commentReport()
        {
            return $this->belongs_to('CommentReport');
        }

}