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
				<h1>Users</h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
		    <div class="panel panel-default">
		        <div class="panel-heading">
		        	All Users
		        </div>
		        <!-- /.panel-heading -->
		        <div class="panel-body">
		            <div class="dataTable_wrapper">
		                <table class="table table-striped table-bordered table-hover" id="users_table">
		                    <thead>
		                        <tr>
		                        	<th>Name</th>
		                        	<th>Email</th>
		                        	<th>Is Admin</th>
		                        	<th>Is Archived</th>
		                        	<th>Projects</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse ($users as $user) 
		                    	<tr>
		                    		<td><a href="{{ URL::to('/users/'.$user->id.'/profile') }}">{{$user->name}}</a></td>
		                    		<td><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>
		                    		<td>{{ $user->is_admin ? "Yes" : "No" }}</td>
		                    		<td>{{ $user->is_archived ? "Yes" : "No" }}</td>
		                    		<td>{{ count($user->projects) }}</td>
		                    	</tr>
		                    	@empty
		                    	<p>No users</p>
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
        $('#users_table').DataTable({
                responsive: true
        });
    });
    </script>
@stop