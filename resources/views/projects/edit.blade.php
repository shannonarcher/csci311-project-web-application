@extends("dashboard.master")

@section('stylesheets')
    <!-- DataTables CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Datepicker CSS -->
    <link href="{{ URL::to('/') }}/css/datepicker.min.css" rel="stylesheet" />

    <style>

        .search_results {
            position:relative;
        }

        #role_search_results {
            list-style: none;
            padding:0;
            margin:0;
            position:absolute;
            z-index:100;
            width:95%;
            background:#fff;
            border-left:solid 1px #eee;
            border-right:solid 1px #eee;
            max-height:200px;
            overflow-y: scroll;
        }

        #role_search_results li {
            padding:3px 2.5%;
            line-height:30px;
            cursor:pointer;
            background:#fff;
        }

        #role_search_results li:first-child {
            border-top:solid 1px #eee;
            background:#f8f8f8;
        }

        #role_search_results li:last-child {
            border-bottom:solid 1px #eee;
        }

        #roles_to_add .label {
            margin-right:5px;
        }

        .role-label {
            position:relative;
        }
        .role-label .label-danger {
            display:none;
            position:absolute;
            bottom:-2px;
            top:1px;
            left:0;
            right:0;
        }
        .role-label:hover .label-danger {
            display:block;
        }
    </style>
@stop

@section('content')        
    @if (isset($message))
    <div class="row" style="padding-top:30px;">
        <div class="col-lg-12">    
            <div class="alert alert-success">
            {{$message}}
            </div>
        </div>
    </div>
    @endif     
    @if (isset($error_message))
    <div class="row" style="padding-top:30px;">
        <div class="col-lg-12">    
            <div class="alert alert-danger">
            {{$error_message}}
            </div>
        </div>
    </div>
    @endif
	<div class="row">
		<div class="col-lg-12">
			<div class="page-header">
				<h1>{{ $project->name }} 
                    <small>
                        <a href='{{ URL::to("/projects/$project->id/dashboard") }}' class="btn btn-sm btn-default"><i class="fa fa-cube fa-fw"></i> @lang('general.dashboard')</a>
                    </small>
                </h1>
			</div>
		</div>
	</div>
	<!-- details -->
	<div class="row">
		<div class="col-lg-12">
            <form role="form" method="post">
     			<div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-book fa-fw"></i> @lang('general.details')
                        <button class="btn btn-xs btn-success pull-right" type="submit"><i class="fa fa-save fa-fw"></i> @lang('general.save')</button>
                    </div>
                    <div class="panel-body">
                    	<fieldset>
                    		<div class="col-md-6">
		                        <div class="form-group">
		                            <label>@lang('general.name')</label>
		                            <input type="text" name="name" value="{{ $project->name }}" placeholder="" class="form-control" />
		                        </div>	   
                            </div>
                            <div class="col-md-6">                 	
		                        <div class="form-group">
		                            <label>@lang('general.start_date')</label>
		                            <input data-datepicker type="text" name="started_at" value="{{ \App\Services\Moment::format($project->started_at, 'Y-m-d h:i:s', 'Y-m-d') }}" placeholder="" class="form-control" />
		                        </div>
                                <div class="form-group">
                                    <label>@lang('general.expected_completion_date')</label>
                                    <input data-datepicker type="text" name="expected_completed_at" value="{{ \App\Services\Moment::format($project->expected_completed_at, 'Y-m-d h:i:s', 'Y-m-d') }}" placeholder="" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label>@lang('general.actual_completion_date')</label>
                                    <input data-datepicker type="text" name="actual_completed_at" value="@if ($project->actual_completed_at != null) {{ \App\Services\Moment::format($project->actual_completed_at, 'Y-m-d h:i:s', 'Y-m-d') }} @endif" placeholder="In Progress" class="form-control" />
                                </div>
		                    </div>
	                    </fieldset>
    	            </div>
                </form>
	        </div>
            <!-- /.panel -->
		</div>

		<div class="col-lg-12">
 			<div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-users fa-fw"></i> @lang('general.project_users')
                </div>
                <div class="panel-body">
                	<div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="project_users_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('general.email')</th>
                                    <th>@lang('general.skills')</th>
                                    <th>@lang('general.roles')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach ($project->users as $p_user)
                                <tr>
                                    <td>{{$p_user->id}}</td>
                                    <td><a href="{{URL::to('/users/'.$p_user->id.'/profile')}}">{{$p_user->name}}</a></td>
                                    <td><a href="mailto:{{$p_user->email}}">{{$p_user->email}}</a></td>
                                    <td>
                                    	<p style="max-width:500px;">
                                    	@foreach ($p_user->skills as $skill)
                                    	<span class="label label-default">{{ $skill->name }}</span>
                                    	@endforeach
                                    	</p>
                                    </td>
                                    <td>
                                        <p style="max-width:500px;">
                                        @foreach ($p_user->roles as $role) 
                                        @if ($role->pivot->assigned_for == $project->id)
                                        <a class="role-label" href="{{ URL::to('projects/'.$project->id.'/removeRoleFromUser/'.$p_user->id.'?roles[0]='.$role->id) }}">
                                            <span class="label label-default">{{ $role->name }}</span>
                                            <span class="label label-danger">@lang('general.remove')</span>
                                        </a>
                                        @endif
                                        @endforeach
                                        </p>
                                    </td>
                                    <td>
                                        <p class="hide">{{ ($p_user->pivot->is_manager ? trans('general.yes') : trans('general.no')) }}</p>
                                    	<div class="btn-group btn-group-xs">
	                                    	<a class="{{ ($p_user->pivot->is_manager ? 'hide' : '') }} btn btn-xs btn-primary" href="{{ URL::to('/projects/'.$project->id.'/promote/'.$p_user->id) }}">@lang('general.promote')</a>
	                                    	<a class="{{ ($p_user->pivot->is_manager ? '' : 'hide') }} btn btn-xs btn-danger" href="{{ URL::to('/projects/'.$project->id.'/demote/'.$p_user->id) }}">@lang('general.demote')</a>
	                                    	<a class="btn btn-xs btn-default" href="{{ URL::to('projects/'.$project->id.'/detachUser/'.$p_user->id) }}">@lang('general.remove')</a>
                                            <a class="btn btn-xs btn-default" 
                                                    data-action="add_role_to_existing_user"
                                                    data-roles="{{ json_encode($p_user->roles) }}"
                                                    href="{{ URL::to('projects/'.$project->id.'/addRoleToUser/'.$p_user->id) }}">@lang('general.add_role')</a>
	                                    </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

	            </div>
	        </div>
            <!-- /.panel -->
        </div>


		<div class="col-lg-12">
 			<div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-users fa-fw"></i> @lang('general.add_to_team')
                </div>
                <div class="panel-body">
                	<div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="all_users_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('general.email')</th>
                                    <th>@lang('general.skills')</th>
                                    <th>@lang('general.roles')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach ($users as $a_user)
                                @if (!$a_user->on_team)
                                <tr>
                                    <td>{{$a_user->id}}</td>
                                    <td><a href="{{URL::to('/users/'.$user->id.'/profile')}}">{{$a_user->name}}</a></td>
                                    <td><a href="mailto:{{$user->email}}">{{$a_user->email}}</a></td>
                                    <td>
                                    	<p style="max-width:500px;">
                                    	@foreach ($a_user->skills as $skill)
                                    	<span class="label label-default">{{ $skill->name }}</span>
                                    	@endforeach
                                    	</p>
                                    </td>
                                    <td>
                                        <p style="max-width:500px;">
                                        @foreach ($a_user->roles as $role)
                                        <span class="label label-default">{{ $role->name }}</span>
                                        @endforeach
                                        </p>
                                    </td>
                                    <td>
                                    	<a href="{{ URL::to('/projects/'.$project->id.'/attachUser/'.$a_user->id) }}" class="btn btn-xs btn-primary" data-action="add_to_team"><i class="fa fa-user-plus fa-fw"></i> @lang('general.add_to_team')</a>
                                    </td>
                                </tr>
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

	            </div>
	        </div>
            <!-- /.panel -->
        </div>
	</div>

    <div class="modal fade" id="roles_modal">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            <h4 class="modal-title">@lang('general.select_role')</h4>
          </div>
          <div class="modal-body">
            <form method="GET" action="" id="role_form">
                <div class="form-group">
                    <input type="text" name="role" class="form-control" id="role_search" autocomplete="off" placeholder="@lang('general.search_roles')">
                    <ul id="role_search_results">

                    </ul>
                </div>
                <div id="roles_to_add">

                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">@lang('general.close')</button>
            <button type="button" class="btn btn-primary" id="save_roles">@lang('general.save_changes')</button>
          </div>
        </div><!-- /.modal-content -->
      </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
@stop


@section('scripts')
    <!-- DataTables JavaScript -->
    <script src="{{ URL::to('/') }}/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>
    <script src="{{ URL::to('/') }}/js/datepicker.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        var roles = [];
        var selected_roles = [];

        $('#project_users_table, #all_users_table').DataTable({
                responsive: true
        });

        $("body").on('click', '[data-action=add_to_team]', function (e) {
            $("#role_form").attr('action', $(this).attr('href'));
            $("#roles_modal").modal();

            e.preventDefault();
            return false;
        });

        $("body").on('change, keyup', '#role_search', function (e) {            
            var search_val = $(this).val().toLowerCase().trim();
            var result_html  = "";

            if (search_val.length > 0) {
                for (var i = 0; i < roles.length; i++) {
                    if (roles[i].name.toLowerCase().indexOf(search_val) > -1) {
                        result_html += "<li data-action='add_role' class='role_list_item' data-id='" + roles[i].id + "'>" + roles[i].name + "</li>";
                    }
                }

                result_html = "<li data-action='add_role' class='role_list_item' data-id='0' class='search_term'>Add Role: " + $(this).val() + "</li>" + result_html;
            }

            $("#role_search_results").html(result_html);
        });

        $("body").on('click', '[data-action=add_role_to_existing_user]', function (e) {
            $('#role_form').attr('action', $(this).attr('href'));
            $("#roles_modal").modal();

            selected_roles = JSON.parse($(this).attr('data-roles'));
            updateRolesList(selected_roles);

            e.preventDefault();
            return false;
        });

        $("body").on('click', '[data-action=add_role]', function (e) {            
            var name = $(this).text();
            name = name.replace("Add Role:", "").trim();

            selected_roles.push({ id: $(this).attr('data-id'), name: name});
            updateRolesList(selected_roles);

            $('#role_search').val('');
            $('#role_search_results').html('');
        });

        $("body").on('click', '#save_roles', function (e) {
            $("#role_form").submit();
        })

        function updateRolesList(e) {

            e.sort(function (a,b) {
                if (a.name > b.name) 
                    return 1;
                else if (a.name < b.name)
                    return -1;
                return 0;
            });

            var roles_html = "";
            for (var i = 0; i < e.length; i++) {
                roles_html += "<span class='label label-default'>" + e[i].name + "<input type='hidden' name='roles[" + i + "]' value='" + e[i].id + ":" + e[i].name + "' /></span>";
            }

            $("#roles_to_add").html(roles_html);
        }

        function getRoles() {
            $.ajax({
                url:"{{ URL::to('/ajax/roles') }}",
                method:"GET"
            }).success(function (e) {
                roles = e;
            });
        }
        getRoles();

        // some datepicker 
        $("[data-datepicker]").datepicker({
            format:'yyyy-mm-dd'
        });
    });
    </script>
@stop