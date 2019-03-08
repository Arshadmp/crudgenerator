@extends('[[custom_master]]')
@section('content')
<style>

.links
{
    padding: 15px;
    margin-top: 5px;
    margin-bottom: 105px;
    text-decoration: none;
}
</style>
<div class="contents">
    <h2>{{ ucfirst('[[model_plural]]') }}</h2>
    <a class="btn btn-success" href="{{ URL::route('[[route_path]].create') }}">Add New</a>
</div>

<table 
id = "table" 
data-toggle = "table"
data-url = "{{url('[[route_path]]/getData') }}" 
class = "table table-bordered" 
data-search = "true"
data-pagination = "true"
data-side-pagination = "server"
data-silent-sort = "false"
data-page-list = "[5, 10, 20, 50, 100, 200]"
>
<thead>
    <tr>
         <th data-field="row">Sl No</th>
        [[foreach:newcolumns]]
        [[if:i.name!='id']]
        <th data-sortable="true" data-field="[[i.name]]">[[i.display]]</th>
        [[endif]]
        [[endforeach]]
        <th data-field="id" data-formatter="actionButtons">Actions</th>
    </tr>
</thead>
</table> 


@endsection
@section('scripts')
<script type = "text/javascript">
    function actionButtons(id) {
            return '<a class="btn btn-secondary mr-1" href="{{url('[[route_path]]')}}/'+id+'"><i class="fa fa-eye" aria-hidden="true"></i></a>' +
          '<a class="btn btn-primary mr-1" href="{{ url('[[route_path]]') }}/'+id+'/edit" ><i class="fa fa-pen" aria-hidden="true"></i></a>'+
          '<a data-id="' + id + '" id="delete-item" class="btn btn-danger text-white mr-1"><i class="fa fa-trash" aria-hidden="true"></i></a>';

    }
    $("body").on("click", "#delete-item", function(){
      if(confirm('You really want to delete this record?')) {
          var id = $(this).data("id");
          
          $.ajax({ url: '{{ url('/[[route_path]]') }}/' + id, type: 'DELETE'}).success(function() {
           location.reload(); 
       });
      }
  });    

</script>
@endsection