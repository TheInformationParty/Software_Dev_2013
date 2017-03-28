<?php 
	$upVoteCount = count($comment->upVotes()->get());
?>

<div class="row-fluid">
	<div class="span6 well <?php print("comment-depth".$comment->depth()); ?>" id="comment<?php print($comment->id)?>">
		<!-- Show user(reputation) - date
		body
		controls -->
		<!-- TODO: Show whether this is endorsing or protesting -->
		<p>
			{{ HTML::link_to_route('profile_view', $comment->user->username." (".$comment->user->reputation.")", $comment->user->id) }} - 
			@if($comment->isendorse)
				Endorse
			@else
				Protest
			@endif - 
			{{ $upVoteCount }} {{ Str::plural('upVote', $upVoteCount) }}
			<br/>
			<?php $date_today = new DateTime('NOW'); 
			$date_created = new DateTime($comment->created_at); ?>
			{{ "submitted ".TimeHelper::get_timespan_string($date_created, $date_today)." ago" }}
		</p>
		<p class="comment-body">
			{{ $comment->body_as_html() }}
		</p>
		<p>
			<?php 
				$links = $comment->links()->get();
			?>
			@if(count($links)>0)
				<ul class="links nav nav-tabs nav-stacked">
					@foreach( $links as $link )
						<li class="comment-link"><a href="{{ $link->url }}">{{ $link->name }}</a></li>
					@endforeach
				</ul>
			@endif
		</p>
		<p class="comment-reply-link">
			{{ HTML::link_to_route('add_comment_reply',"reply &#8594;", $comment->id) }}
		</p>
		<!-- TODO: Disable this button if the user has already upvoted this comment -->
		<p class="upvote-button">
			@if(Auth::check())
				@if(Auth::user()->has_upvoted($comment))
					<button type="submit" class="btn" disabled>Upvote</button>
				@else
					{{ Form::open('upvote_comment', 'POST') }}
					{{ Form:: token() }}
					{{ Form::hidden('comment_id', $comment->id) }}
					{{ Form::submit('Upvote') }}
					{{ Form::close() }}
				@endif
			@else
				{{ Form::open('upvote_comment', 'POST') }}
				{{ Form:: token() }}
				{{ Form::hidden('comment_id', $comment->id) }}
				{{ Form::submit('Upvote') }}
				{{ Form::close() }}
			@endif
		</p>
	</div>
</div>
