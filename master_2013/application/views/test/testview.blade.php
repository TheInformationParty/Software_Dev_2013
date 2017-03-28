@layout('layouts.default')

@section('content')
	<div class="row-fluid">
		<h2>See if your new stuff works</h2>
		<p>
			I'm rooting for you.{{$stances[0]->id}}
		</p>

	</div>
@endsection
