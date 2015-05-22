@extends("dashboard.master")

@section('stylesheets')
    <!-- DataTables CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <style>
    #gannt {
        height:400px;
        width:100%;
        outline:none;
        border:solid 1px #eee;
    }
    </style>
@stop

@section('content')
	<div class="row">
		<div class="col-lg-12">  
			<div class="page-header">
				<h1>{{$project->name}}
                    <small>
                        <div class="btn-group btn-group-sm">
                            <a href="{{ URL::to('/projects/'.$project->id.'/dashboard') }}" class="btn btn-sm btn-default">@lang('general.dashboard')</a>
                        </div>
                    </small>
                </h1>
			</div>
		</div>
	</div>
    <div class="row">        
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> @lang('general.notifications')
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        @foreach($notifications as $n)
                        <a href='{{ URL::to("/projects/$project->id/tasks/".$n->task->id) }}' class="list-group-item">
                            <i class="fa fa-tasks fa-fw"></i>
                            {{ trans('general.'.$n->notification) }}: {{ $n->task->title }}
                            <span class="pull-right text-muted small">
                                <em>{{ $n->task->created_at }}</em>
                            </span>
                        </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop