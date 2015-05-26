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
                @if (isset($success_message))
                <div class="alert alert-success">
                {{$success_message}}
                </div>
                @endif
                @if (isset($error_message))
                <div class="alert alert-danger">
                {{$error_message}}
                </div>
                @elseif ($project->archived_at != null)
                <div class="alert alert-warning">@lang('general.archived')</div>
                @endif
				<h1>
                    {{$project->name}}

                    <small>
                        <div class="btn-group btn-group-sm">
                            @if ($user->is_admin)
                            <a href="{{ URL::to('/projects/'.$project->id.'/dashboard/edit') }}" class="btn btn-sm btn-default"> <i class="fa fa-pencil fa-fw"></i> @lang('general.edit')</a>
                            @else
                            @foreach ($project->managers as $manager)
                            @if ($manager->id == $user->id)
                            <a href="{{ URL::to('/projects/'.$project->id.'/dashboard/edit') }}" class="btn btn-sm btn-default"> <i class="fa fa-pencil fa-fw"></i> @lang('general.edit')</a>
                            @endif
                            @endforeach
                            @endif
                        </div>
                        <div class="btn-group btn-group-sm">
                            <a href='{{ URL::to("/projects/$project->id/gantt") }}' target="_blank" class="btn btn-sm btn-default"><i class="fa fa-sitemap fa-fw"></i> @lang('general.gantt')</a>
                            <a href='{{ URL::to("/projects/$project->id/apn") }}' target="_blank" class="btn btn-sm btn-default"><i class="fa fa-sitemap fa-fw"></i> @lang('general.apn')</a>
                            <a href='{{ URL::to("/projects/$project->id/pert") }}' target="" class="btn btn-sm btn-default"><i class="fa fa-calculator fa-fw"></i> @lang('general.pert')</a>
                            <!--<a href='{{ URL::to("/projects/$project->id/criticalChain") }}' target="" class="btn btn-sm btn-default"><i class="fa fa-calculator fa-fw"></i> @lang('general.critical_chain')</a>-->
                        </div>
                        <div class="btn-group btn-group-sm">
                            <a href='{{ URL::to("/projects/$project->id/functionPoints") }}' target="" class="btn btn-sm btn-default"><i class="fa fa-cubes fa-fw"></i> @lang('general.function_points')</a>
                            <a href='{{ URL::to("/projects/$project->id/cocomo") }}' target="" class="btn btn-sm btn-default"><i class="fa fa-space-shuttle fa-fw"></i> @lang('general.cocomo')</a>
                        </div>
                    </small>
                </h1>
			</div>
		</div>
	</div>

    <div class="row">
        @if (isset($project->function_points))
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-danger">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-cubes fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ \App\Services\AlbrechtFP::calculateFP($project) }}</div>
                            <div>@lang('general.function_points')</div>
                        </div>
                    </div>
                </div>
                <a href='{{ URL::to("/projects/$project->id/functionPoints") }}'>
                    <div class="panel-footer">
                        <span class="pull-left">@lang('general.recalculate')</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if ($project->system_type_id > 0)
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-warning">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-fighter-jet fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ \App\Services\COCOMO::calculateCOCOMO1($project) }} @lang('general.pm')</div>
                            <div>@lang('general.cocomoI')</div>
                        </div>
                    </div>
                </div>
                <a href='{{ URL::to("/projects/$project->id/cocomo") }}'>
                    <div class="panel-footer">
                        <span class="pull-left">@lang('general.recalculate')</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if (isset($project->cocomoii))
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-success">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-space-shuttle fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ \App\Services\COCOMO::calculateCOCOMO2($project) }} @lang('general.pm')</div>
                            <div>@lang('general.cocomoII')</div>
                        </div>
                    </div>
                </div>
                <a href='{{ URL::to("/projects/$project->id/cocomo") }}'>
                    <div class="panel-footer">
                        <span class="pull-left">@lang('general.recalculate')</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        @endif

        @if (isset($project->pert)) 
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-calculator fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $project->pert }}%</div>
                            <div>@lang('general.pert') @lang('general.success')</div>
                        </div>
                    </div>
                </div>
                <a href='{{ URL::to("/projects/$project->id/pert") }}'>
                    <div class="panel-footer">
                        <span class="pull-left">@lang('general.view_full_breakdown')</span>
                        <span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
                        <div class="clearfix"></div>
                    </div>
                </a>
            </div>
        </div>
        @endif
    </div>

    <!-- details -->
    <div class="row">
        <div class="col-md-6 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-book fa-fw"></i> @lang('general.details')
                    @if ($user->is_admin)
                    <a href="{{ URL::to('/projects/'.$project->id.'/dashboard/edit') }}" class="btn btn-xs btn-primary pull-right"> <i class="fa fa-pencil fa-fw"></i> @lang('general.edit')</a>
                    @else
                    @foreach ($project->managers as $manager)
                    @if ($manager->id == $user->id)
                    <a href="{{ URL::to('/projects/'.$project->id.'/dashboard/edit') }}" class="btn btn-xs btn-primary pull-right"> <i class="fa fa-pencil fa-fw"></i> @lang('general.edit')</a>
                    @endif
                    @endforeach
                    @endif
                </div>
                <div class="panel-body">
                    <form role="form">
                        <fieldset disabled>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label>@lang('general.name')</label>
                                    <p class="form-control-static">{{$project->name}}</p>
                                </div>
                                
                                <div class="form-group">
                                    <label>@lang('general.created_by')</label>
                                    <p class="form-control-static">
                                        <a href="{{ URL::to('/users/'.$project->created_by->id.'/profile') }}">{{$project->created_by->name}}</a>
                                    </p>
                                </div>
                            </div>
                            <div class="col-lg-6">                              
                                <div class="form-group">
                                    <label>@lang('general.start_date')</label>
                                    <p class="form-control-static">
                                        {{ \App\Services\Moment::fromNow($project->started_at, "Y-m-d H:i:s") }} <br>
                                        <small class="date">{{ \App\Services\Moment::format($project->started_at, "Y-m-d H:i:s", "Y-m-d") }}</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>@lang('general.expected_completed_date')</label>
                                    <p class="form-control-static">
                                        {{ \App\Services\Moment::fromNow($project->expected_completed_at, "Y-m-d H:i:s") }} <br>
                                        <small class="date">{{ \App\Services\Moment::format($project->expected_completed_at, "Y-m-d H:i:s", "Y-m-d") }}</small>
                                    </p>
                                </div>
                                <div class="form-group">
                                    <label>@lang('general.actual_completed_date')</label>
                                    <p class="form-control-static">
                                        @if ($project->actual_completed_at)
                                            {{ \App\Services\Moment::fromNow($project->actual_completed_at, "Y-m-d H:i:s") }}<br>
                                            <small class="date">{{ \App\Services\Moment::format($project->actual_completed_at, "Y-m-d H:i:s", "Y-m-d") }}</small>
                                        @else
                                            @lang('general.in_progress')
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
        <div class="col-md-6 col-xs-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-bell fa-fw"></i> @lang('general.notifications')
                </div>
                <div class="panel-body">
                    <div class="list-group">
                        @foreach($notifications as $n)
                        <a href='{{ URL::to("/projects/$project->id/tasks/".$n->task->id) }}' class="list-group-item">
                            <i class="fa fa-tasks fa-fw"></i>
                            {{ trans('general.'.$n->notification) }}: {{ $n->task->id }}
                            <span class="pull-right text-muted small">
                                <em>{{ \App\Services\Moment::fromNow($n->task->created_at, "Y-m-d H:i:s") }}</em>
                            </span>
                        </a>
                        @endforeach
                    </div>
                    @if (count($notifications) >= 6)
                    <a href="{{ URL::to('/projects/'.$project->id.'/notifications') }}" class="btn btn-default btn-block"> <i class="fa fa-eye fa-fw"></i> @lang('general.view_all_alerts')</a>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-tasks fa-fw"></i> @lang('general.remaining_tasks')
                    @if ($user->is_admin)
                    <a href="{{ URL::to('/projects/'.$project->id.'/tasks') }}" class="btn btn-xs btn-primary pull-right"> <i class="fa fa-eye fa-fw"></i> @lang('general.view_all')</a>     
                    @else             
                    @foreach ($project->users as $team_member)
                    @if ($team_member->id == $user->id)
                    <a href="{{ URL::to('/projects/'.$project->id.'/tasks') }}" class="btn btn-xs btn-primary pull-right"> <i class="fa fa-eye fa-fw"></i> @lang('general.view_all')</a>
                    @endif
                    @endforeach  
                    @endif      
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
                                @foreach ($project->tasks as $task)
                                @if ($task->progress < 100 && $task->approved_at)
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
                                @endif
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
            <!-- /.panel -->
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-calendar-o fa-fw"></i> @lang('general.milestones')

                    @if ($user->is_admin)
                    <a href="{{ URL::to('/projects/'.$project->id.'/milestones') }}" class="btn btn-xs btn-primary pull-right"> <i class="fa fa-eye fa-fw"></i> @lang('general.view_all')</a>
                    @else
                    @foreach ($project->managers as $manager)
                    @if ($manager->id == $user->id)
                    <a href="{{ URL::to('/projects/'.$project->id.'/milestones') }}" class="btn btn-xs btn-primary pull-right"> <i class="fa fa-eye fa-fw"></i> @lang('general.view_all')</a>
                    @endif
                    @endforeach
                    @endif
                </div>
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="task_table">
                            <thead>
                                <tr>
                                    <th>@lang('general.title')</th>
                                    <th>@lang('general.date')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($project->milestones as $milestone)
                                <tr>
                                    <td>{{$milestone->title}}</td>
                                    <td>
                                        <small class="hide">{{ \App\Services\Moment::format($milestone->completed_at, "Y-m-d H:i:s", "Y-m-d") }}</small>
                                        {{ \App\Services\Moment::fromNow($milestone->completed_at, "Y-m-d H:i:s") }}
                                        <small class="date">{{ \App\Services\Moment::format($milestone->completed_at, "Y-m-d H:i:s", "Y-m-d") }}</small>
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
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-users fa-fw"></i> @lang('general.users')                    
                    @if ($user->is_admin)
                    <a href="{{ URL::to('/projects/'.$project->id.'/dashboard/edit') }}" class="btn btn-xs btn-primary pull-right"> <i class="fa fa-pencil fa-fw"></i> @lang('general.edit')</a>
                    @else
                    @foreach ($project->managers as $manager)
                    @if ($manager->id == $user->id)
                    <a href="{{ URL::to('/projects/'.$project->id.'/dashboard/edit') }}" class="btn btn-xs btn-primary pull-right"> <i class="fa fa-pencil fa-fw"></i> @lang('general.edit')</a>
                    @endif
                    @endforeach
                    @endif
                </div>
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="users_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('general.email')</th>
                                    <th>@lang('general.is_manager')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($project->users as $p_user)
                                <tr>
                                    <td>{{$p_user->id}}</td>
                                    <td><a href="{{URL::to('/users/'.$p_user->id.'/profile')}}">{{$p_user->name}}</a></td>
                                    <td><a href="mailto:{{$p_user->email}}">{{$p_user->email}}</a></td>
                                    <td>
                                        {{$p_user->pivot->is_manager ? trans('general.yes') : trans('general.no') }}
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
        $('#users_table, #task_table').DataTable({
                responsive: true
        });
    });
    </script>
@stop