@layout('layouts.default')

@section('content')
	<div class="row-fluid">
		<h2>Learn the terrain.</h2>

		<p>
			You've landed yourself on our default home page. The route that
			is generating this page lives at:
		</p>

		<pre>{{ path('app') }}routes.php</pre>

		<p>And the view sitting before you can be found at:</p>

		<pre>{{ path('app') }}views/home/index.blade.php</pre>
		
		<div class="row-fluid">
			<div class="span6">
				<h2>Grow in knowledge.</h2>

				<p>
					Learning to use Laravel is amazingly simple thanks to
					its {{ HTML::link('docs', 'wonderful documentation') }}.
				</p>
			</div>					

			<div class="span6">
				<h2>Create something beautiful.</h2>

				<p>
					Now that you're up and running, it's time to start creating!
					Here are some links to help you get started:
				</p>
			</div>
		</div>
		
		<div class="row-fluid">
			<div class="span4">
				<a href="http://laravel.com">Official Website</a>
			</div>
			<div class="span4">
				<a href="http://forums.laravel.com">Laravel Forums</a>
			</div>
			<div class="span4">
				<a href="http://github.com/laravel/laravel">GitHub Repository</a>
			</div>
		</div>
		<div class="row-fluid">
			<h3>Test Links</h3>
			<ul>
			@if(Auth::check())
				<li>{{ HTML::link_to_route('profile_view', "View your profile", Auth::user()->id) }}</li>
				<li>{{ HTML::link_to_route('profile_update', "Edit your profile", Auth::user()->id) }}</li>
				<li>{{ HTML::link_to_route('your_stances', 'See your Stances') }}</li>
			@endif
				<li>{{ HTML::link_to_route('all_stances', 'See all Stances') }}</li>
				<li>{{ HTML::link_to_route('add_stance', 'Add a Stance') }}</li>
			</ul>
		</div>
	</div>
@endsection
