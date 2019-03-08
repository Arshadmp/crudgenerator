@extends('[[custom_master]]')

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



<h2 class="page-header">[[model_uc]]</h2>

<div class="panel panel-default">
    <div class="panel-heading">
        <h3></h3>
    </div>

    <div class="panel-body">

        <form action="{{ url('/[[route_path]]'.( isset($model) ? '/' . $model->id : '')) }}" method="POST" class="form-horizontal">
            {{ csrf_field() }}

            @if (isset($model))
            <input type="hidden" name="_method" value="PATCH">
            @endif


            [[foreach:columns]]
            [[if:i.type=='id']]
             
            <div class="form-group">
                <div class="col-sm-6">
                    <input type="hidden" name="[[i.name]]" id="[[i.name]]" class="form-control" value="{{isset($model)?$model['[[i.name]]']:null}}">
                </div>
            </div>

            [[endif]]
            [[if:i.type=='text']]
            <div class="form-group">
                <label for="[[i.name]]" class="col-sm-3 control-label">[[i.display]]</label>
                <div class="col-sm-6">
                    <input type="text" name="[[i.name]]" id="[[i.name]]" class="form-control" value="{{isset($model)?$model['[[i.name]]']:null}}">
                </div>
            </div>
            [[endif]]
            [[if:i.type=='number']]
            <div class="form-group">
                <label for="[[i.name]]" class="col-sm-3 control-label">[[i.display]]</label>
                <div class="col-sm-6">
                    <input type="number" name="[[i.name]]" id="[[i.name]]" class="form-control" value="{{isset($model)?$model['[[i.name]]']:null}}">
                </div>
            </div>
            [[endif]]
            [[if:i.type=='date']]
            <div class="form-group">
                <label for="[[i.name]]" class="col-sm-3 control-label">[[i.display]]</label>
                <div class="col-sm-6">
                    <input type="date" name="[[i.name]]" id="[[i.name]]" class="form-control" value="{{isset($model)?$model['[[i.name]]']:null}}">
                </div>
            </div>
            [[endif]]
            [[if:i.type=='textarea']]
            <div class="form-group">
                 <label for="[[i.name]]" class="col-sm-3 control-label">[[i.display]]</label>
                 <div class="col-sm-6">
                 <textarea class="form-control" name="[[i.name]]" rows="5" id="[[i.name]]">{{isset($model)?$model['[[i.name]]']:null}}</textarea>
                </div>
            </div>

            </div>
            [[endif]]
            [[if:i.type=='unknown']]
            <div class="form-group">
                <label for="[[i.name]]" class="col-sm-3 control-label">[[i.display]]</label>
                <div class="col-sm-6">
                    <input type="text" name="[[i.name]]" id="[[i.name]]" class="form-control" value="{{isset($model)?$model['[[i.name]]']:null}}">
                </div>
            </div>
            [[endif]]
            [[endforeach]]

            <div class="form-group">
                <div class="col-sm-offset-3 col-sm-6">
                    <button type="submit" class="btn btn-success">
                        <i class="fa fa-plus"></i> Save
                    </button> 
                    <a class="btn btn-info" href="{{ url('/[[route_path]]') }}"><i class="fas fa-arrow-left"></i>Back</a>
                </div>
            </div>
        </form>

    </div>
</div>
@endsection