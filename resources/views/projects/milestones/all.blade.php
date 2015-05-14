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
                
				<h1>{{$project->name}} @lang('general.milestones')
                    <small>
                        <a href="{{ URL::to('/projects/'.$project->id.'/dashboard') }}" class="btn btn-default btn-sm">Project Dashboard</a>
                    </small>
                </h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
 			<div class="panel panel-default">
                <div class="panel-heading">
                    @lang('general.milestones')
                    <a href="{{ URL::to('/projects/'.$project->id.'/milestones/add') }}" class="btn btn-xs btn-primary pull-right">@lang('general.add_milestone')</a>               
                </div>
                <div class="panel-body">
		            <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="milestone_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('general.title')</th>
                                    <th>@lang('general.date')</th>
                                    <th>@lang('general.actions')</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach ($milestones as $milestone)
                                <tr>
                                    <td>{{$milestone->id}}</td>
                                    <td>{{$milestone->title}}</td>
                                    <td>{{$milestone->completed_at}}</td>
                                    <td>
                                        <a class="btn btn-xs btn-danger" href="{{ URL::to('/projects/'.$project->id.'/milestones/'.$milestone->id.'/remove') }}">Remove</a>
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
	<!-- projects -->
@stop

@section('scripts')
    <!-- DataTables JavaScript -->
    <script src="{{ URL::to('/') }}/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        $('#users_table, #milestone_table').DataTable({
                responsive: true
        });
    });
    </script>
@stop