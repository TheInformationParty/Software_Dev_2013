@layout('layouts.default')

@section('content')
	@if($errors->has())
		<!-- Note, this errors object was passed from a validation attempt. -->
		<div class="alert-error row-fluid">
			<ul>
				<?php $messages = $errors->all('<li>:message</li>') ?>
				@foreach($messages as $message)
					{{ $message }}
				@endforeach
			</ul>
		</div>
	@endif
	{{ Form::open('add_comment', 'POST') }}

	{{ Form:: token() }}
	<!-- author -->
	<!-- Form::hidden('author_id', $user->id) -->

		<p>
		    {{ Form::label('body', 'Comment') }}
		    {{ Form::textarea('body', Input::old('body')) }}
		</p>
		<p>
			My comment is 
			{{ Form::select('endorsing', array('1' => 'Endorsing', '0' => 'Protesting'), 'Endorsing'); }}
			this stance.
		</p>
			{{ Form::hidden('stance_id', $stance_id) }}
		<p>
		    {{ Form::submit('Add Comment') }}
		</p>

	{{ Form::close() }}

@endsection