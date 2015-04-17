@extends("dashboard.master")

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
				<h1>{{$project->name}}</h1>
			</div>
		</div>
	</div>
	<!-- details -->
	<div class="row">
		<div class="col-lg-12">
 			<div class="panel panel-default">
                <div class="panel-heading">
                    Details
                </div>
                <div class="panel-body">
                    <form role="form">
                    	<fieldset disabled>
                    		<div class="col-md-6">
		                        <div class="form-group">
		                            <label>Name</label>
		                            <p class="form-control-static">{{$project->name}}</p>
		                        </div>
		                        
		                        <div class="form-group">
		                            <label>Created By</label>
		                            <p class="form-control-static">
		                            	<a href="{{ URL::to('/users/'.$project->created_by->id.'/profile') }}">{{$project->created_by->name}}</a>
		                            </p>
		                        </div>
		                    </div>
		                    <div class="col-md-6">		                    	
		                        <div class="form-group">
		                            <label>Start Date</label>
		                            <p class="form-control-static">{{$project->started_at}}</p>
		                        </div>
		                        <div class="form-group">
		                            <label>Expected Completion Date</label>
		                            <p class="form-control-static">{{$project->expected_completed_at}}</p>
		                        </div>
		                        <div class="form-group">
		                            <label>Actual Completion Date</label>
		                            <p class="form-control-static">
		                            	@if ($project->actual_completed_at)
		                            		{{$project->actual_completed_at}}
		                            	@else
		                            		In Progress
		                            	@endif
		                            </p>
		                        </div>
		                    </div>
	                    </fieldset>
	                </form>
	            </div>
	        </div>
            <!-- /.panel -->
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
 			<div class="panel panel-default">
                <div class="panel-heading">
                    Tasks
                </div>
                <div class="panel-body">
		            <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="task_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Title</th>
                                    <th>Progress</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach ($project->tasks as $task)
                                <tr>
                                    <td>{{$task->id}}</td>
                                    <td><a href="{{URL::to('/projects/'.$project->id.'/tasks/'.$task->id)}}">{{$task->title}}</a></td>
                                    <td>{{$task->progress}}%</td>
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
                    Users
                </div>
                <div class="panel-body">
                	<div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="users_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Is Manager</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@forelse ($project->users as $user)
                                <tr>
                                    <td>{{$user->id}}</td>
                                    <td><a href="{{URL::to('/users/'.$user->id.'/profile')}}">{{$user->name}}</a></td>
                                    <td><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>
                                    <td>
                                    	{{$user->pivot->is_manager ? "Yes" : "No"}}
                                    </td>
                                </tr>
                                @empty
                                <p>No users assigned to project.</p>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- /.table-responsive -->

	            </div>
	        </div>
            <!-- /.panel -->
        </div>
	</div>
	<!-- projects -->
@stop

@section('scripts')
    <!-- DataTables JavaScript -->
    <script src="{{ URL::to('/') }}/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#users_table, #task_table').DataTable({
                responsive: true
        });
    });
    </script>
@stop