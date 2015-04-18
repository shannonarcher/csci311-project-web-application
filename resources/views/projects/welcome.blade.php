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
				<h1>Projects</h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
		    <div class="panel panel-default">
		        <div class="panel-heading">
		        	All Projects
		        	@if ($user->is_admin)
		        	<a href="{{ URL::to('projects/add') }}" class="btn-xs btn btn-primary pull-right">Add Project</a>
		        	@endif
		        </div>
		        <!-- /.panel-heading -->
		        <div class="panel-body">
		            <div class="dataTable_wrapper">
		                <table class="table table-striped table-bordered table-hover" id="projects_table">
		                    <thead>
		                        <tr>
		                        	<td>#</td>
		                        	<td>Name</td>
		                        	<td>Team</td>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse ($projects as $project) 
		                    	<tr>
		                    		<td>{{$project->id}}</td>
		                    		<td><a href="{{ URL::to('/projects/'.$project->id.'/dashboard') }}">{{$project->name}}</a></td>
		                    		<td>
		                    			@foreach ($project->users as $user)
		                    			<a href="{{ URL::to('/user/'.$user->id.'/profile') }}">{{ $user->name }}</a>, 
		                    			@endforeach
		                    		</td>	
		                    	</tr>
		                    	@empty
		                    	<p>No projects</p>
		                    	@endforelse
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