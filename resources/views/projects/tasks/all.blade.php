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
				<h1>
                    {{$project->name}}@lang('general.stasks') 
                    <a href='{{ URL::to("/projects/$project->id/dashboard") }}' class="btn btn-sm btn-default"><i class="fa fa-cube fa-fw"></i> @lang('general.dashboard')</a>
                </h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
 			<div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-tasks fa-fw"></i> @lang('general.tasks')
                    <a href="{{ URL::to('/projects/'.$project->id.'/tasks/add') }}" class="btn btn-xs btn-primary pull-right"><i class="fa fa-plus fa-fw"></i> @lang('general.task')</a>               
                </div>
                <div class="panel-body">
		            <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="task_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('general.title')</th>
                                    <th>@lang('general.priority')</th>
                                    <th>@lang('general.progress')</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach ($tasks as $task)
                                <tr>
                                    <td>{{$task->id}}</td>
                                    <td><a href="{{URL::to('/projects/'.$project->id.'/tasks/'.$task->id)}}">{{$task->title}}</a></td>
                                    <td>                                        
                                        @if ($task->priority == "low")
                                        Low
                                        @elseif ($task->priority == "med")
                                        Medium
                                        @elseif ($task->priority == "high")
                                        High
                                        @else
                                        Unspecified
                                        @endif
                                    </td>
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