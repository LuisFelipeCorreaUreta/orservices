@extends('backLayout.app')
@section('title')
Suggestion Form
@stop
<style>
    tr.modified{
        background-color: red !important;
    }

    tr.modified > td{
        background-color: red !important;
        color: white;
    }
    #btn_create {
    	margin: 0 auto;
    	width: 250px;
    }
    #btn_delete {
    	margin: 0 auto;
    	width: 150px;
    }

    /* for switch */
    .material-switch > input[type="checkbox"] {
        display: none;
    }

    .material-switch > label {
        cursor: pointer;
        height: 0px;
        position: relative;
        width: 40px;
    }

    .material-switch > label::before {
        background: rgb(0, 0, 0);
        box-shadow: inset 0px 0px 10px rgba(0, 0, 0, 0.5);
        border-radius: 8px;
        content: '';
        height: 16px;
        margin-top: -8px;
        position:absolute;
        opacity: 0.3;
        transition: all 0.4s ease-in-out;
        width: 40px;
        left: -4px;
    }
    .material-switch > label::after {
        background: rgb(255, 255, 255);
        border-radius: 16px;
        box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.3);
        content: '';
        height: 24px;
        left: -4px;
        margin-top: -8px;
        position: absolute;
        top: -4px;
        transition: all 0.3s ease-in-out;
        width: 24px;
    }
    .material-switch > input[type="checkbox"]:checked + label::before {
        background: inherit;
        opacity: 0.5;
    }
    .material-switch > input[type="checkbox"]:checked + label::after {
        background: inherit;
        left: 20px;
    }
</style>
@section('content')

<div class="row">

  <div class="col-md-12 col-sm-12 col-xs-12">
    <div class="x_panel">
      <div class="x_title">
        <h2>Suggestion Form</h2>
        <div class="clearfix"></div>
      </div>
      <div class="x_content" style="overflow: scroll;">

        <!-- <table class="table table-striped jambo_table bulk_action table-responsive"> -->
        <table id="suggest_table" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                	<th class="text-center">No</th>
                    <th class="text-center">Timestamp</th>
                    <th class="text-center">Organization</th>
                    <th class="text-center">Message</th>
                    <th class="text-center">Name</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Phone</th>
                </tr>
            </thead>
            <tbody>
            @if($suggests)
              @foreach($suggests as $key => $suggest)
                <tr id="suggest{{$suggest->suggest_recordid}}">
                  <td class="text-center">{{$key+1}}</td>
                  <td class="text-center">{{$suggest->created_at}}</td>
                  <td class="text-center">@if($suggest->suggest_organization)
                    <span class="badge bg-blue">{{$suggest->organization ? $suggest->organization->organization_name : ''}}</span>
                  @endif
                  </td>
                  <td><span style="white-space:normal;">{{$suggest->suggest_content}}</span></td>
                  <td class="text-center">{{$suggest->suggest_username}}</td>
                  <td class="text-center">{{$suggest->suggest_user_email}}</td>
                  <td class="text-center">{{$suggest->suggest_user_phone}}</td>
                </tr>
              @endforeach
            @endif
            </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-12 col-sm-12 col-xs-12" style="text-align: center;">
    <div class="x_panel" style="width: 50%;">
      <div class="x_title" style="text-align: center;">
        <h4>Forward submission to the following email addresses:</h4>
        <div class="clearfix"></div>
      </div>

      <div class="x_content" style="overflow: scroll;">
        <table id="email_table" class="display nowrap table-striped jambo_table table-bordered table-responsive" cellspacing="0" width="100%">
            <thead>
                <tr>
                	<th class="text-center">No</th>
                    <th class="text-center">Email</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            @if (sizeof($emails) > 0)
              	@foreach($emails as $key => $email)
	                <tr id="suggest{{$suggest->suggest_recordid}}">
	                  <td class="text-center">{{$key+1}}</td>
	                  <td class="text-center">{{$email->email_info}}</td>
	                  <td class="text-center">
	                    <button id="btn_delete" class="btn btn-block btn-danger btn-sm btn_delete open_modal" value="{{ $email->email_recordid }}" ><i class="fa fa-fw fa-trash"></i>Delete</button>
	                  </td>
	                </tr>
	            @endforeach
	        @endif
            </tbody>
        </table>
      </div>
      <div class="col-md-12 pull-left" style="margin: 15px 0px;">
        {{-- <label class="control-label col-md-8 col-sm-8 col-xs-8 text-left">Show/Hide Suggestion Form</label> --}}
        <label class="control-label col-md-8 col-sm-8 col-xs-8 text-left">Only logged in users can submit suggestions</label>
        {{-- <div class="col-md-8 col-sm-8 col-xs-12">
            <label>Off&nbsp;&nbsp;
            <input type="checkbox" class="js-switch" value="1" name="show_suggest_menu" id="show_suggest_menu" {{ $layout && $layout->show_suggest_menu == '1' ? 'checked'  : '' }} />&nbsp;&nbsp;On
            </label>
        </div> --}}
        <div class="col-md-3 col-sm-3 col-xs-3">
            <div class="material-switch pull-right">
                <input id="show_suggest_menu" name="show_suggest_menu" type="checkbox" {{ $layout && $layout->show_suggest_menu == '1' ? 'checked'  : '' }} />
                <label for="show_suggest_menu" class="label-primary"  style="margin: 15px 0px;"></label>
            </div>
        </div>
    </div>
      <div class="col-md-12 text-center"  style="margin: 15px 0px;">
        <button id="btn_create" class="btn btn-block btn-primary btn-sm open_modal" data-toggle="modal" data-target=".bs-create-modal-lg" >Add</button>
      </div>

    </div>
  </div>

  	<div class="modal fade bs-delete-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="/email_delete_filter" method="POST" id="email_delete_filter">
                    {!! Form::token() !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Delete Email</h4>
                    </div>
                    <div class="modal-body">

                        <input type="hidden" id="email_recordid" name="email_recordid">
                        <h4>Are you sure to delete this email?</h4>

                    </div>
                    @if(!empty($email))
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Close</button>
                        <button type="submit" id="email_delete_btn" class="btn btn-danger btn-delete"  >Delete</button>
                    </div>
                    @endif
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade bs-create-modal-lg" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="/email_create_filter" method="POST" id="email_create_filter">
                    {!! Form::token() !!}
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span>
                        </button>
                        <h4 class="modal-title" id="myModalLabel">Add a New Email</h4>
                    </div>
                    <div class="modal-body">
	                    <label class="control-label sel-label-org pl-4">Email: </label>
	                    <div class="col-md-12 col-sm-12 col-xs-12 contact-email-div">
	                        <input class="form-control selectpicker" type="email" id="contact_email" name="contact_email" value="" required>
	                    </div>
                    </div>

                    <div class="modal-footer" style="margin-top: 40px;">
                        <button type="button" class="btn btn-success" data-dismiss="modal">Close</button>
                        <button type="submit" id="email_create_btn" class="btn btn-primary btn-create">Add</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


</div>

@endsection

@section('scripts')

<script type="text/javascript">
$(document).ready(function() {

    $('#suggest_table').DataTable( {
        responsive: {
            details: {
                renderer: function ( api, rowIdx, columns ) {
                    var data = $.map( columns, function ( col, i ) {
                        return col.hidden ?
                            '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<td>'+col.title+':'+'</td> '+
                                '<td>'+col.data+'</td>'+
                            '</tr>' :
                            '';
                    } ).join('');

                    return data ?
                        $('<table/>').append( data ) :
                        false;
                }
            }
        },
        "paging": false,
        "pageLength": 20,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false
    } );

    $('#email_table').DataTable( {
        responsive: {
            details: {
                renderer: function ( api, rowIdx, columns ) {
                    var data = $.map( columns, function ( col, i ) {
                        return col.hidden ?
                            '<tr data-dt-row="'+col.rowIndex+'" data-dt-column="'+col.columnIndex+'">'+
                                '<td>'+col.title+':'+'</td> '+
                                '<td>'+col.data+'</td>'+
                            '</tr>' :
                            '';
                    } ).join('');

                    return data ?
                        $('<table/>').append( data ) :
                        false;
                }
            }
        },
        "paging": false,
        "pageLength": 20,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": false,
        "autoWidth": false
    } );

    $('.btn_delete').on('click', function(e) {
        e.preventDefault();
        var value = $(this).val();
        $('#email_recordid').val(value);
        $('.bs-delete-modal-lg').modal('show')
    });

    $('#email_delete_btn').on('click', function(e) {
        e.preventDefault();
        $('#email_delete_filter').submit();
    });
    $('#show_suggest_menu').change(function(){
        let value = $(this).is(":checked");
        let show_suggest_menu;
        if(value){
            show_suggest_menu = 1
        }else{
            show_suggest_menu = 0
        }
        var _token = "{{ csrf_token() }}";
        $.ajax({
            url: "{{ route('contact_form.store') }}",
            method:"post",
            data:{_token,show_suggest_menu},
            success:function(data){
            },
            error : function(err){
            }
        })
    })


} );
</script>
@endsection
