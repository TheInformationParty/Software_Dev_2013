@layout('layouts.default')

@section('content')
		@foreach($stances->results as $stance)
			{{ render("stance.preview", array('stance'=>$stance)) }}
		@endforeach

		{{ $stances->links() }}
@endsection

@section('sidebar')
	@if(empty($stances))
		It appears there aren't any stances! Why not {{ HTML::link_to_route('add_stance', 'add one') }}?
	@else
		<div>
			<h4>Filter</h4>
			{{ Form::open('filter_stances', 'POST', array('onsubmit' => 'return false;')) }}

			{{ Form::token() }}

			{{ Form::label('type', 'Type:') }}
			{{ Form::select('type', array('All' => 'All', 'Platform' => 'Platform', 'Candidate' => 'Candidate', 'Legislation'=>'Legislation')) }}

			{{ Form::label('region', 'Region(s):') }}
			<ul class="tagit" id="region-tags">
			</ul>
			<div class="clear"></div>
			<input type="text" class="span3" id="region-typeahead" data-provide="typeahead" autocomplete="off"></input>
			<input type="hidden" class="hidden-ids" id="region-ids" name="region-ids">

			{{ Form::label('tags', 'Tag(s):') }}
			<ul class="tagit" id="stancetag-tags">
			</ul>
			<div class="clear"></div>
			<input type="text" class="span3" id="stancetag-typeahead" data-provide="typeahead" autocomplete="off"></input>
			<input type="hidden" class="hidden-ids" id="stancetag-ids" name="stancetag-ids">

			</br>
			{{-- Custom submit button so that the form doesn't submit when users press enter --}}
			<input class="submit-button btn" type="button" value="Filter" onclick="this.parentNode.submit();">

			{{ Form::close() }}
		</div>
	@endif
@endsection