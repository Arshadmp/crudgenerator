
@extends('crudgenerator::layout')

@section('content')

@if ($errors->any())
<div class="alert alert-danger">
	<ul>
		@foreach ($errors->all() as $error)
		<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@endif





<div class="card-body">

	<form method="post" action="{{ route('crudgenerator.generate') }}">
		<div class="form-group">
			@csrf
			<label for="name">Model Name:</label>
			<input type="text" class="form-control" name="model_name"/>
		</div>

		<button type="submit" class="btn btn-primary">Create</button>
	</form>





</div>
 <script>
    var msg = '{{Session::get('alert')}}';
    var exist = '{{Session::has('alert')}}';
    if(exist){
      alert(msg);
    }
  </script>
  @endsection

