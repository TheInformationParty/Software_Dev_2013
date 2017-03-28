@layout('layouts.default')

@section('content')
	{{ Form::open('login', 'POST') }}

	{{ Form:: token() }}

	<p>
		{{ Form::label('email', 'Email') }}<br />
		{{ Form::text('email', Input::old('email')) }}
	</p>
	<p>
		{{ Form::label('password', 'Password') }}<br />
		{{ Form::password('password') }}
	</p>
	<p>
		{{ Form::submit('Login') }}
	</p>

	{{ Form::close() }}
@endsection
