@extends('layout')


@section('content')
	<style >
		body
		{
		  width: 100%;
		  background-image: url("{{ asset('images/bg.jpg') }}");
		  background-repeat: no-repeat;
		  height: 100%;
        }
        .welcome{
        	text-align: center;
        	margin: 10% auto 20%;
        }
        p{
        	font-size: 3em;
            
        }
        h1{

        	font-size: 4em;
        	margin-top: 70px;
        	margin-left: 50px;
        }

	</style>
<h1>CRUD generator</h1>


  
</div>
@endsection
