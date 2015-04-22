@extends("dashboard.master")

@section('stylesheets')
    <!-- DataTables CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
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
	<div class="row">
		<div class="col-lg-12">
			<div class="page-header">
				<h1>{{ $project->name }}</h1>
			</div>
		</div>
	</div>
	<!-- details -->
	<div class="row">
		<div class="col-lg-12">
            <form role="form" method="post">
     			<div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('general.details')
                        <button class="btn btn-xs btn-success pull-right" type="submit">@lang('general.save')</button>
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
		                            <input type="text" name="started_at" value="{{ $project->started_at }}" placeholder="" class="form-control" />
		                        </div>
                                <div class="form-group">
                                    <label>@lang('general.expected_completion_date')</label>
                                    <input type="text" name="expected_completed_at" value="{{ $project->expected_completed_at }}" placeholder="" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label>@lang('general.actual_completion_date')</label>
                                    <input type="text" name="actual_completed_at" value="{{ $project->actual_completed_at }}" placeholder="In Progress" class="form-control" />
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
                    @lang('general.project_users')
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
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach ($project->users as $p_user)
                                <tr>
                                    <td>{{$p_user->id}}</td>
                                    <td><a href="{{URL::to('/users/'.$user->id.'/profile')}}">{{$p_user->name}}</a></td>
                                    <td><a href="mailto:{{$user->email}}">{{$p_user->email}}</a></td>
                                    <td>
                                    	<p style="max-width:500px;">
                                    	@foreach ($p_user->skills as $skill)
                                    	{{ $skill->name }}, 
                                    	@endforeach
                                    	</p>
                                    </td>
                                    <td>
                                        <p class="hide">{{ ($p_user->pivot->is_manager ? trans('general.yes') : trans('general.no')) }}</p>
                                    	<div class="btn-group btn-group-xs">
	                                    	<a class="{{ ($p_user->pivot->is_manager ? 'hide' : '') }} btn btn-xs btn-primary" href="{{ URL::to('/projects/'.$project->id.'/promote/'.$p_user->id) }}">@lang('general.promote')</a>
	                                    	<a class="{{ ($p_user->pivot->is_manager ? '' : 'hide') }} btn btn-xs btn-danger" href="{{ URL::to('/projects/'.$project->id.'/demote/'.$p_user->id) }}">@lang('general.demote')</a>
	                                    	<a class="btn btn-xs btn-default" href="{{ URL::to('projects/'.$project->id.'/detachUser/'.$p_user->id) }}">@lang('general.remove')</a>
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
                    @lang('general.all_users')
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
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach ($users as $a_user)
                                <tr>
                                    <td>{{$a_user->id}}</td>
                                    <td><a href="{{URL::to('/users/'.$user->id.'/profile')}}">{{$a_user->name}}</a></td>
                                    <td><a href="mailto:{{$user->email}}">{{$a_user->email}}</a></td>
                                    <td>
                                    	<p style="max-width:500px;">
                                    	@foreach ($a_user->skills as $skill)
                                    	{{ $skill->name }}, 
                                    	@endforeach
                                    	</p>
                                    </td>
                                    <td>
                                    	<a href="{{ URL::to('/projects/'.$project->id.'/attachUser/'.$a_user->id) }}" class="btn btn-xs btn-primary">@lang('general.add_to_team')</a>
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
	</div>
@stop


@section('scripts')
    <!-- DataTables JavaScript -->
    <script src="{{ URL::to('/') }}/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#project_users_table, #all_users_table').DataTable({
                responsive: true
        });
    });
    </script>
@stop