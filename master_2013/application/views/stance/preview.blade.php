<div class="row-fluid">
	<div class="span6 well stance-list">
		<div class="span9">
			<p>
				<h4>{{ HTML::link_to_route('stance_view', $stance->name, $stance->id) }}</h4>
				<?php 
					$date_today = new DateTime('NOW'); 
					$date_created = new DateTime($stance->created_at);
				?>
				{{ "submitted ".TimeHelper::get_timespan_string($date_created, $date_today)." ago" }} by {{ HTML::link_to_route('profile_view', $stance->creator->username." (".$stance->creator->reputation.")" , $stance->creator->id) }}
			</p>
			<p>{{ $stance->body_as_html() }}</p>
			@if(count($stance->links()->get())>0)
				Links:
				<ul class="links nav nav-tabs nav-stacked">
					@foreach( $stance->links()->get() as $link )
						<li class="link"><a href="{{ $link->url }}">{{ $link->name }}</a></li>
					@endforeach
				</ul>
			@endif
			<p>
				Tags:
				@foreach( $stance->stanceTags()->get() as $tag )
					<li class="tag">{{ $tag->tag }}</li>
				@endforeach
			</p>
		</div>
		<div class="span3">
			<p>
				Endorsements: {{ $stance->count_endorsements() }}
			</p>
			<p>
				Protests: {{ $stance->count_protests() }}
			</p>
			<p>
				Comments: {{ $stance->comments()->count() }}
			</p>
		</div>
	</div>
</div>
<!-- TODO: Add most upvote endorse and protest comment -->
