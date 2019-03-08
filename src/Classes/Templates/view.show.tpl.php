@extends('[[custom_master]]')

@section('content')
<style>
  .wrapper
  {
    margin: 15% auto 10%;
  }
</style>

<div class = "wrapper">
    <table class = "table table-bordered  table-hover">
<thead>
[[foreach:columns]]
[[if:i.type=='text']]
<tr>
<th>[[i.display]]</th>
<td>{{$[[model_singular]]->[[i.name]]}}</td>
</tr> 
[[endif]]
[[if:i.type=='number']]
<tr>
<th>[[i.display]]</th>
<td>{{$[[model_singular]]->[[i.name]]}}</td>
</tr> 
[[endif]]
[[if:i.type=='textarea']]
<tr>
<th>[[i.display]]</th>
<td>{{$[[model_singular]]->[[i.name]]}}</td>
</tr> 
[[endif]]
[[if:i.type=='date']]
<tr>
<th>[[i.display]]</th>
<td>{{date('d-M-Y', strtotime($[[model_singular]]->[[i.name]]))}}</td>
</tr> 
[[endif]]
[[if:i.type=='timestamp']]
<tr>
<th>[[i.display]]</th>
<td>{{date('d-M-Y H:i:s', strtotime($[[model_singular]]->[[i.name]]))}}</td>
</tr> 
[[endif]]
[[endforeach]]
</table>

</div>

@endsection