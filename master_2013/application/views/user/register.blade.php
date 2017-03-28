@layout('layouts.default')

@section('content')
	@if($errors->has())
		<div class="alert-error row-fluid">
			<!-- Note, this errors object was passed from a validation attempt. -->
			<div class="alert-error row-fluid">
				<ul>
					<?php $messages = $errors->all('<li>:message</li>') ?>
					@foreach($messages as $message)
						{{ $message }}
					@endforeach
				</ul>
			</div>
		</div>
	@endif
	<div class="row-fluid">
		<div class="span6">
			{{ Form::open('register', 'POST') }}

			{{ Form::token() }}

			{{ Form::label('username', 'Username') }}
			{{ Form::text('username', Input::old('username')) }}

			{{ Form::label('email', 'Email') }}
			{{ Form::text('email', Input::old('email')) }}

			{{ Form::label('password', 'Password') }}
			{{ Form::password('password') }}

			{{ Form::label('password_confirmation', 'Confirm Password') }}
			{{ Form::password('password_confirmation') }}

			{{ Form::label('firstName', 'First Name') }}
			{{ Form::text('firstName', Input::old('firstName')) }}

			{{ Form::label('lastName', 'Last Name') }}
			{{ Form::text('lastName', Input::old('lastName')) }}

			{{ Form::label('postalcode', 'Postal Code') }}
			{{ Form::text('postalcode', Input::old('postalcode')) }}
		</div>
		<div class="span6">
			{{ Form::label('description', 'Tell us about yourself') }}
			{{ Form::textarea('description', Input::old('description')) }}

			{{ Form::label('race', 'Race') }}
			{{ Form::text('race', Input::old('race')) }}

			{{ Form::label('gender', 'Gender') }}
			{{ Form::text('gender', Input::old('gender')) }}

			{{ Form::label('birthdate', 'Birthdate (YYYY-MM-DD)') }}
			{{ Form::text('birthdate', Input::old('birthdate')) }}

			</br>
			{{ Form::submit('Register') }}	

			{{ Form::close() }}
		</div>
	</div>
@endsection