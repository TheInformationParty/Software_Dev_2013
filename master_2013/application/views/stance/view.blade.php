@layout('layouts.default')

@section('content')
<div class="stance row-fluid">
	<div class="span9 well">
		<div class="span9">
			<h2>{{ $stance->type }} Stance: {{ $stance->name }}</h2>
			<h3>{{ $stance->region->longname }}</h3>
			<?php 
				$date_today = new DateTime('NOW'); 
				$date_created = new DateTime($stance->created_at);
			?>
			<h4>{{ "submitted ".TimeHelper::get_timespan_string($date_created, $date_today)." ago" }} by {{ HTML::link_to_route('profile_view', $stance->creator->username." (".$stance->creator->reputation.")" , $stance->creator->id) }}</h4>
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
					@if(Auth::check())
						@if(Auth::user()->can_endorse($stance))
							{{ Form::open('endorse_stance', 'POST') }}
							{{ Form:: token() }}
							<p>
								{{ Form::submit('Endorse') }}
								{{ Form::hidden('stance_id', $stance->id)}}<br/>
								Endorsements: {{ $stance->count_endorsements() }}
							</p>
							{{ Form::close() }}
						@else
							<button type="submit" class="btn" disabled>Endorse</button>
							Endorsements: {{ $stance->count_endorsements() }}
						@endif
					@else
						{{ Form::open('endorse_stance', 'POST') }}
						{{ Form:: token() }}
						<p>
							{{ Form::submit('Endorse') }}
							{{ Form::hidden('stance_id', $stance->id)}}<br/>
							Endorsements: {{ $stance->count_endorsements() }}
						</p>
						{{ Form::close() }}
					@endif
				</p>
				<p>
					@if(Auth::check())
						@if(Auth::user()->can_protest($stance))
							{{ Form::open('protest_stance', 'POST') }}
							{{ Form:: token() }}
							<p>
								{{ Form::submit('Protest') }}
								{{ Form::hidden('stance_id', $stance->id)}}<br/>
								Protests: {{ $stance->count_protests() }}
							</p>
							{{ Form::close() }}
						@else
							<button type="submit" class="btn" disabled>Protest</button>
							Protests: {{ $stance->count_protests() }}
						@endif
					@else
						{{ Form::open('protest_stance', 'POST') }}
						{{ Form:: token() }}
						<p>
							{{ Form::submit('Protest') }}
							{{ Form::hidden('stance_id', $stance->id)}}<br/>
							Protests: {{ $stance->count_protests() }}
						</p>
						{{ Form::close() }}
					@endif
				</p>	
				{{ HTML::link_to_route('add_comment',"Add a comment &#8594;", $stance->id)}}			
		</div>
	</div>
</div>
@foreach($stance->sorted_comments() as $comment)
	{{ render("comment.view", array('comment'=>$comment)) }}
@endforeach

@endsection