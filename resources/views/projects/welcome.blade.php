@extends('dashboard/master')

@section('stylesheets')
    <!-- DataTables CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
@stop

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<div class="page-header">
				<h1>@lang('general.projects')
					<small>
						@if ($user->is_admin)
			        	<a href="{{ URL::to('projects/add') }}" class="btn-sm btn btn-default">@lang('general.add_project')</a>
			        	@endif
					</small>
				</h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
		    <div class="panel panel-default">
		        <div class="panel-heading">
		        	@lang('general.all_projects')
		        	@if ($user->is_admin)
		        	<a href="{{ URL::to('projects/add') }}" class="btn-xs btn btn-primary pull-right">@lang('general.add_project')</a>
		        	@endif
		        </div>
		        <!-- /.panel-heading -->
		        <div class="panel-body">
		            <div class="dataTable_wrapper">
		                <table class="table table-striped table-bordered table-hover" id="projects_table">
		                    <thead>
		                        <tr>
		                        	<td>#</td>
		                        	<td>@lang('general.name')</td>
		                        	<td>@lang('general.team')</td>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    	@foreach ($projects as $project) 
		                    	<tr>
		                    		<td>{{$project->id}}</td>
		                    		<td><a href="{{ URL::to('/projects/'.$project->id.'/dashboard') }}">{{$project->name}}</a></td>
		                    		<td>
		                    			@foreach ($project->users as $p_user)
		                    			<a href="{{ URL::to('/users/'.$p_user->id.'/profile') }}">{{ $p_user->name }}</a>, 
		                    			@endforeach
		                    		</td>	
		                    	</tr>
		                    	@endforeach
		                    </tbody>
		                </table>
		            </div>
		        </div>
		        <!-- /.panel-body -->
		    </div>
		    <!-- /.panel -->
		</div>
		<!-- /.col-lg-12 -->
	</div>
@stop

@section('scripts')
    <!-- DataTables JavaScript -->
    <script src="{{ URL::to('/') }}/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#projects_table').DataTable({
                responsive: true
        });
    });
    </script>
@stop