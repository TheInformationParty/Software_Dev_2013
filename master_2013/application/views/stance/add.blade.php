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
	<div class="row-fluid">
		
		{{ Form::open('add_stance', 'POST') }}

		{{ Form::token() }}

		{{ Form::label('name', 'Title:') }}
		{{ Form::text('name', Input::old('name')) }}

		{{ Form::label('type', 'Type:') }}
		{{ Form::select('type', array('Platform' => 'Platform', 'Candidate' => 'Candidate', 'Legislation'=>'Legislation')) }}

		<?php 
			//get the logged in user's regions for the selectbox
			$regions = Auth::user()->regions()->get();
			$regionSelectOptions = array();
			foreach($regions as $region){
				$regionSelectOptions[$region->id] = $region->longname;
			}
		?>

		{{ Form::label('region', 'Region:') }}
		{{ Form::select('region', $regionSelectOptions) }}

		{{ Form::label('body', 'Content:') }}
		{{ Form::textarea('body', Input::old('body')) }}

		{{ Form::label('tags', 'Tags: (up to 3)') }}
		{{ Form::text('tags', Input::old('tags')) }}

		</br>
		{{ Form::submit('Submit') }}	

		{{ Form::close() }}
		
	</div>
@endsection