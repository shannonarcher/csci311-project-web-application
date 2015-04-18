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
				<h1>Add Project</h1>
			</div>
		</div>
	</div>
	<!-- details -->
	<div class="row">
		<div class="col-lg-12">
            <form role="form" method="post">
     			<div class="panel panel-default">
                    <div class="panel-heading">
                        Details
                        <button class="btn btn-xs btn-success pull-right" type="submit">Save</button>
                    </div>
                    <div class="panel-body">
                    	<fieldset>
                    		<div class="col-lg-6">
		                        <div class="form-group">
		                            <label>Name</label>
		                            <input type="text" name="name" value="" placeholder="" class="form-control" />
		                        </div>	                    	
		                        <div class="form-group">
		                            <label>Start Date</label>
		                            <input type="text" name="started_at" value="" placeholder="" class="form-control" />
		                        </div>
		                        <div class="form-group">
		                            <label>Expected Completion Date</label>
		                            <input type="text" name="expected_completed_at" value="" placeholder="" class="form-control" />
		                        </div>
                                <div class="form-group">
                                    <label>Initial Managers</label>
                                    <p class="form-static-control" id="manager_list">
                                        No managers assigned.
                                    </p>
                                </div>
		                    </div>
                            <div class="col-lg-6">
                                <div class="dataTable_wrapper">
                                    <table class="table table-striped table-bordered table-hover" id="users_table">
                                        <thead>
                                            <tr>
                                                <th>Name</th>
                                                <th>Email</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $user) 
                                            <tr>
                                                <td><a href="{{ URL::to('/users/'.$user->id.'/profile') }}">{{$user->name}}</a></td>
                                                <td><a href="mailto:{{$user->email}}">{{$user->email}}</a></td>
                                                <td>
                                                    <a data-user="{{ json_encode($user) }}" href="#" id="add_as_manager" class="btn btn-xs btn-primary">Add</a>
                                                    <a data-user="{{ json_encode($user) }}" href="#" id="remove_as_manager" class="btn btn-xs btn-danger hide">Remove</a>
                                                </div>
                                            </tr>
                                            @empty
                                            <p>No users</p>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
	                    </fieldset>
    	            </div>
                </form>
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
        var managers = [];

        $('#users_table').DataTable({
                responsive: true
        });

        $('body').on('click', '#add_as_manager', function () {
            if (managers.length < 50) {
                var user = JSON.parse($(this).attr('data-user'));
                managers.push(user);
                updateManagerList(managers);

                $(this).addClass('hide');
                $(this).siblings().removeClass('hide');
            } else {
                alert("Maximum team sized reached.");
            }
        });

        $('body').on('click', '#remove_as_manager', function () {
            var user = JSON.parse($(this).attr('data-user'));
            for (var i = 0; i < managers.length; i++) {
                if (managers[i].id == user.id)
                    managers.splice(i, 1);
            };
            updateManagerList(managers);

            $(this).addClass('hide');
            $(this).siblings().removeClass('hide');
        }); 

        function updateManagerList() {
            var list = "No managers assigned.";
            if (managers.length > 0) {
                list = "";
                for (var i = 0; i < managers.length; i++) {
                    list += "<input type='hidden' name='managers[" + i + "]' value='" + managers[i].id + "]' />";
                    list += managers[i].name + ', ';
                }
            }
            $("#manager_list").html(list);
        }
        updateManagerList();
    });
    </script>
@stop