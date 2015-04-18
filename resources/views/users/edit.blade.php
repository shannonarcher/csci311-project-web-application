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
                @if (isset($success_message))
                <div class="alert alert-success">
                {{$success_message}}
                </div>
                @endif
				<h1>
                    {{$profile->name}}
                </h1>
			</div>
		</div>
	</div>
	<!-- details -->
	<div class="row">
		<div class="col-lg-6">
            <form role="form" id="details_form" action="" method="post">
     			<div class="panel panel-default">
                    <div class="panel-heading">
                        Details                    
                        <button class="btn btn-xs btn-success pull-right" type="submit">Save</button>
                    </div>
                    <div class="panel-body">
                    	<fieldset>
	                        <div class="form-group">
	                            <label>Name</label>
                                <input class="form-control" type="text" value="{{$profile->name}}" name="name" />
	                        </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input class="form-control" type="text" value="{{$profile->email}}" name="email" />
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input class="form-control" placeholder="Enter new password to change" type="text" value="" name="password" />
                            </div>                         
                            @if ($user->is_admin)
	                        <div class="form-group">   
	                            <div class="checkbox">
	                                <label>
	                                    <input type="checkbox" name="is_admin" {{$profile->is_admin ? 'checked' : ''}}> Administrator
	                                </label>
	                            </div>
	                            <div class="checkbox">
	                                <label>
	                                    <input type="checkbox" name="is_archived" {{$profile->is_archived ? 'checked' : ''}}> Archived
	                                </label>
	                            </div>
	                        </div>
                            @endif
	                    </fieldset>
    	            </div>
                </div>
            </form>
            <!-- /.panel -->
		</div>
		<div class="col-lg-6">
 			<div class="panel panel-default">
                <div class="panel-heading">
                    Projects
                </div>
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="projects_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Name</th>
                                    <th>Is Manager</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@forelse ($profile->projects as $project)
                                <tr>
                                    <td>{{$project->id}}</td>
                                    <td><a href="{{URL::to('/projects/'.$project->id.'/dashboard')}}">{{$project->name}}</a></td>
                                    <td>
                                    	{{$project->pivot->is_manager ? "Yes" : "No"}}
                                    </td>
                                </tr>
                                @empty
                                <p>No projects for user.</p>
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
        $('#projects_table').DataTable({
                responsive: true
        });
    });
    </script>
@stop