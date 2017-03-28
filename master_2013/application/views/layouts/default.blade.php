<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	<title>{{ $title }}</title>
	<meta name="viewport" content="width=device-width">
	{{ Asset::container('bootstrapper')->styles() }}
	{{ Asset::container('bootstrapper')->scripts() }}
	{{ HTML::style('/css/bootstrap-override.css') }} <!-- Our custom CSS -->
	{{ HTML::script('/js/script.js') }} <!-- Our custom JS -->
	<script type="text/javascript">var BASE = "<?php echo URL::base(); ?>/index.php/";</script>
</head>
<body>
	<div class="container-fluid">
		<div class="row-fluid">
			<div class="span3 sidebar">
				<h1>{{ $title }}</h1>
				<h2>{{ $subtitle }}</h2>
				<ul class="nav">
					<li>{{ HTML::link_to_route('home',"Home") }}</li>
					@if(Auth::check())
						<li>{{ HTML::link_to_route('logout', 'Logout ('.Auth::user()->username.')') }}</li>
						<li>{{ HTML::link_to_route('profile_view', "View your profile", Auth::user()->id) }}</li>
						<li>{{ HTML::link_to_route('profile_update', "Edit your profile", Auth::user()->id) }}</li>
						<li>{{ HTML::link_to_route('your_stances', 'See your Stances') }}</li>
					@else
						<li>{{ HTML::link_to_route('register', 'Register') }}</li>
						<li>{{ HTML::link_to_route('login', 'Login') }}</li> 
					@endif
					<li>{{ HTML::link_to_route('all_stances', 'See all Stances') }}</li>
					<li>{{ HTML::link_to_route('add_stance', 'Add a Stance') }}</li>
				</ul>
				@yield('sidebar')
			</div>
			<div class="span9 content">
				@if(Session::has('error'))
					<p class="alert-error">{{ Session::get('error') }}</p>
				@endif
				@if(Session::has('message'))
					<p class="alert-info">{{ Session::get('message') }}</p>
				@endif
				@yield('content')
			</div>
		</div>
	</div>
</body>
</html>
