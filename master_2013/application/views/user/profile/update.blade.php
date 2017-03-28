@layout('layouts.default')

@section('content')
	@if($errors->has())
		<div class="alert-error row-fluid">
			<p>The following errors have occured:</p>
			<ul>
				{{ $errors->first('username', '<li>:message</li>') }}
				{{ $errors->first('email', '<li>:message</li>') }}
				{{ $errors->first('password', '<li>:message</li>') }}
				{{ $errors->first('password_confirmation', '<li>:message</li>') }}
				{{ $errors->first('firstName', '<li>:message</li>') }}
				{{ $errors->first('lastName', '<li>:message</li>') }}
				{{ $errors->first('postalcode', '<li>:message</li>') }}
				{{ $errors->first('description', '<li>:message</li>') }}
				{{ $errors->first('race', '<li>:message</li>') }}
				{{ $errors->first('gender', '<li>:message</li>') }}
				{{ $errors->first('birthdate', '<li>:message</li>') }}
			</ul>
		</div>
	@endif
	<div class="row-fluid">
		<div class="span6">
			{{ Form::open('profile_update', 'POST') }}

			{{ Form::token() }}

			{{ Form::label('email', 'Email') }}
			{{ Form::text('email', Input::old('email', $user->email)) }}

			{{ Form::label('currentPassword', 'Current Password') }}
			{{ Form::password('currentPassword') }}

			{{ Form::label('newPassword', 'New Password') }}
			{{ Form::password('newPassword') }}

			{{ Form::label('password_confirmation', 'Confirm New Password') }}
			{{ Form::password('password_confirmation') }}

			{{ Form::label('firstName', 'First Name') }}
			{{ Form::text('firstName', Input::old('firstName', $user->firstname)) }}

			{{ Form::label('lastName', 'Last Name') }}
			{{ Form::text('lastName', Input::old('lastName', $user->lastname)) }}

			{{ Form::label('postalcode', 'Postal Code') }}
			{{ Form::text('postalcode', Input::old('postalcode', $user->postalcode->postalcode)) }}
		</div>
		<div class="span6">
			{{ Form::label('description', 'Tell us about yourself') }}
			{{ Form::textarea('description', Input::old('description', $user->description)) }}

			{{ Form::label('race', 'Race') }}
			{{ Form::text('race', Input::old('race', $user->race)) }}

			{{ Form::label('gender', 'Gender') }}
			{{ Form::text('gender', Input::old('gender', $user->gender)) }}

			{{ Form::label('birthdate', 'Birthdate (YYYY-MM-DD)') }}
			{{ Form::text('birthdate', Input::old('birthdate', (new DateTime($user->birthdate))->format("Y-m-d"))) }}

			</br>
			{{ Form::submit('Save') }}	

			{{ Form::close() }}
		</div>
	</div>
@endsection