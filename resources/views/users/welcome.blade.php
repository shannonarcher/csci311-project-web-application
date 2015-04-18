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
				<h1>@lang('general.users')</h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
		    <div class="panel panel-default">
		        <div class="panel-heading">
		        	@lang('general.all_users')
		        	@if ($user->is_admin)
		        	<a href="{{ URL::to('users/add') }}" class="btn-xs btn btn-primary pull-right">@lang('general.add_user')</a>
		        	@endif
		        </div>
		        <!-- /.panel-heading -->
		        <div class="panel-body">
		            <div class="dataTable_wrapper">
		                <table class="table table-striped table-bordered table-hover" id="users_table">
		                    <thead>
		                        <tr>
		                        	<th>@lang('general.name')</th>
		                        	<th>@lang('general.email')</th>
		                        	<th>@lang('general.is_admin')</th>
		                        	<th>@lang('general.is_archived')</th>
		                        	<th>@lang('general.project_count')</th>
		                        </tr>
		                    </thead>
		                    <tbody>
		                    	@forelse ($users as $a_user) 
		                    	<tr>
		                    		<td><a href="{{ URL::to('/users/'.$a_user->id.'/profile') }}">{{$a_user->name}}</a></td>
		                    		<td><a href="mailto:{{$a_user->email}}">{{$a_user->email}}</a></td>
		                    		<td>{{ $a_user->is_admin ? trans("general.yes") : trans("general.no") }}</td>
		                    		<td>{{ $a_user->is_archived ? trans("general.yes") : trans("general.no") }}</td>
		                    		<td>{{ count($a_user->projects) }}</td>
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