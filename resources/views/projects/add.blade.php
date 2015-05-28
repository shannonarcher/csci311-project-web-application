@extends("dashboard.master")

@section('stylesheets')
    <!-- DataTables CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <!-- Datepicker CSS -->
    <link href="{{ URL::to('/') }}/css/datepicker.min.css" rel="stylesheet" />
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
                @if (isset($error_message))
                <div class="alert alert-danger">
                {{$error_message}}
                </div>
                @endif
                <h1>
                    <i class="fa fa-cube fa-fw"></i> @lang('general.add_project')
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
                    		<div class="col-lg-6">
		                        <div class="form-group">
		                            <label>@lang('general.name')</label>
		                            <input type="text" name="name" value="" placeholder="" class="form-control" />
		                        </div>	                    	
		                        <div class="form-group">
		                            <label>@lang('general.start_date')</label>
		                            <input data-datepicker type="text" name="started_at" value="" placeholder="" class="form-control" />
		                        </div>
		                        <div class="form-group">
		                            <label>@lang('general.expected_completion_date')</label>
		                            <input data-datepicker type="text" name="expected_completed_at" value="" placeholder="" class="form-control" />
		                        </div>
                                <div class="form-group">
                                    <label>@lang('general.initial_managers')</label>
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
                                                <th>@lang('general.name')</th>
                                                <th>@lang('general.email')</th>
                                                <th>@lang('general.actions')</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($users as $a_user) 
                                            <tr>
                                                <td><a href="{{ URL::to('/users/'.$a_user->id.'/profile') }}">{{$a_user->name}}</a></td>
                                                <td><a href="mailto:{{$a_user->email}}">{{$user->email}}</a></td>
                                                <td>
                                                    <a data-user="{{ json_encode($a_user) }}" href="#" id="add_as_manager" class="btn btn-xs btn-primary">Add</a>
                                                    <a data-user="{{ json_encode($a_user) }}" href="#" id="remove_as_manager" class="btn btn-xs btn-danger hide">Remove</a>
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
    <script src="{{ URL::to('/') }}/js/datepicker.min.js"></script>

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

        // some datepicker 
        $("[data-datepicker]").datepicker({
            format:'yyyy-mm-dd'
        });
    });
    </script>
@stop