@layout('layouts.default')

@section('content')
	<div class="row-fluid profile">
		<div class="span9 well">
			<!-- TODO: ADD THE USER's REGIONS -->
			<h2>{{$user->username}}</h2>
			{{$user->firstname}} {{$user->lastname}}
			 registered
			<?php 
				$date_today = new DateTime('NOW'); 
				$date_created = new DateTime($user->created_at);
			?>
			{{ TimeHelper::get_timespan_string($date_created, $date_today)." ago" }}
			@if((strcmp($user->description, "")))
				<p>
					<h3>Description</h3>
					<p>{{ $user->description_as_html() }}</p>
					@if(count($user->links()->get())>0)
						<p class="links">Links:
							<ul class="links nav nav-tabs nav-stacked">
								@foreach( $user->links()->get() as $link )
									<li class="link"><a href="{{ $link->url }}">{{ $link->name }}</a></li>
								@endforeach
							</ul>
						</p>
					@endif
				</p>
			@endif
			@if (Auth::check())
				@if ($user->id == Auth::user()->id)
					{{ HTML::link_to_route('profile_update', "Update your Profile &#8594;", Auth::user()->id) }}
				@endif
			@endif
		</div>
	</div>
	<div class="row-fluid"></div>
@endsection