@extends('backLayout.app')
@section('title')
{{$role->is_group ?"Group permissions":"Role permissions"}}

@stop

@section('content')

<div class="row">
	<div class="col-md-12 col-sm-12 col-xs-12">
		<div class="x_panel">
			<div class="x_title">
				<h2>{{$role->is_group ?"Group ":"Role "}}{{$role->name}}</h2>
				<div class="clearfix"></div>
			</div>
			<div class="x_content">
                {{ Form::open(array('url' => route('role.save', $role->id), 'class' => 'form-horizontal')) }}
                    <div class="row">
                        @foreach($actions as $action)
                        <div class="col-md-6">
                            <div class="form-group">
                                <?php $first= array_values($action)[0]; $firstname = explode(".", $first)[0]; ?>
                                {{Form::label($firstname, $firstname, ['class' => 'form col-md-4 text-right capital_letter'])}}
                                <div class="col-md-6">
                                    <select name="permissions[]" class="select form-control" multiple="multiple">
                                        @foreach($action as $act)
                                        @if(explode(".", $act)[0]=="api")
                                        <option value="{{$act}}"
                                            {{$role->permissions && in_array($act, json_decode($role->permissions))? "selected":""}}>
                                            {{isset(explode(".", $act)[2])?explode(".", $act)[1].".".explode(".", $act)[2]:explode(".", $act)[1]}}
                                        </option>
                                        @else
                                        <option value="{{$act}}"
                                            {{$role->permissions && in_array($act, json_decode($role->permissions))?"selected":""}}>

                                            {{explode(".", $act)[1]}}

                                        </option>
                                        @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <div class="form-group">
                        <div class="col-sm-offset-2 col-sm-3">
                            {!! Form::submit('Submit', ['class' => 'btn btn-success']) !!}
                            <a href="{{$role->is_group ? route('group.index'):route('role.index')}}" class="btn btn-primary">Back to list</a>
                        </div>
                    </div>
                {{ Form::close() }}
            </div>
        </div>
    </div>
</div>

@stop
@section('scripts')
<script src="{{ URL::asset('/backend/vendors/sumoselect/jquery.sumoselect.js') }}"></script>
<link href="{{ URL::asset('/backend/vendors/sumoselect/sumoselect.css') }}" rel="stylesheet" />

<script type="text/javascript">
    $('.select').SumoSelect({ selectAll: true, placeholder: 'Nothing selected' });
</script>
@endsection