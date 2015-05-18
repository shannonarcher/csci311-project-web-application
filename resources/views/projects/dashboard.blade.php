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
                        @if ($user->is_admin)
                        <a href="{{ URL::to('/projects/'.$project->id.'/dashboard/edit') }}" class="btn btn-sm btn-default">@lang('general.edit')</a>
                        @else
                        @foreach ($project->managers as $manager)
                        @if ($manager->id == $user->id)
                        <a href="{{ URL::to('/projects/'.$project->id.'/dashboard/edit') }}" class="btn btn-sm btn-default">@lang('general.edit')</a>
                        @endif
                        @endforeach
                        @endif

                        <a href='{{ URL::to("/projects/$project->id/gannt") }}' target="_blank" class="btn btn-sm btn-default">@lang('general.gannt_chart')</a>

                        @if (!isset($project->functionPoints))                        
                        <a href='{{ URL::to("/projects/$project->id/functionPoints") }}' target="" class="btn btn-sm btn-default">@lang('general.calculate_function_points')</a>
                        @endif
                    </small>
                </h1>
			</div>
		</div>
	</div>

    <!--<div class="row">
        <div class="col-lg-12">
            <iframe id="gannt" src="{{ URL::to('/projects/'.$project->id.'/gannt') }}"></iframe>
        </div>
    </div>-->

    <div class="row">
        @if (isset($project->functionPoints))
        <div class="col-lg-3 col-md-6">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <div class="row">
                        <div class="col-xs-3">
                            <i class="fa fa-comments fa-5x"></i>
                        </div>
                        <div class="col-xs-9 text-right">
                            <div class="huge">{{ $project->functionPoints->ufp * $projcet->functionPoints->vap }}</div>
                            <div>@lang('general.function_points')</div>
                        </div>
                    </div>
                </div>
                <a href="#">
                    <div class="panel-footer">
                        <span class="pull-left">@lang('general.recalculate')</span>
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
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('general.details')
                    @if ($user->is_admin)
                    <a href="{{ URL::to('/projects/'.$project->id.'/dashboard/edit') }}" class="btn btn-xs btn-primary pull-right">@lang('general.edit')</a>
                    @else
                    @foreach ($project->managers as $manager)
                    @if ($manager->id == $user->id)
                    <a href="{{ URL::to('/projects/'.$project->id.'/dashboard/edit') }}" class="btn btn-xs btn-primary pull-right">@lang('general.edit')</a>
                    @endif
                    @endforeach
                    @endif
                </div>
                <div class="panel-body">
                    <form role="form">
                        <fieldset disabled>
                            <div class="col-md-6">
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
                            <div class="col-md-6">                              
                                <div class="form-group">
                                    <label>@lang('general.start_date')</label>
                                    <p class="form-control-static">{{$project->started_at}}</p>
                                </div>
                                <div class="form-group">
                                    <label>@lang('general.expected_completed_date')</label>
                                    <p class="form-control-static">{{$project->expected_completed_at}}</p>
                                </div>
                                <div class="form-group">
                                    <label>@lang('general.actual_completed_date')</label>
                                    <p class="form-control-static">
                                        @if ($project->actual_completed_at)
                                            {{$project->actual_completed_at}}
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
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('general.tasks')
                    @if ($user->is_admin)
                    <a href="{{ URL::to('/projects/'.$project->id.'/tasks') }}" class="btn btn-xs btn-primary pull-right">@lang('general.view_all')</a>     
                    @else             
                    @foreach ($project->users as $team_member)
                    @if ($team_member->id == $user->id)
                    <a href="{{ URL::to('/projects/'.$project->id.'/tasks') }}" class="btn btn-xs btn-primary pull-right">@lang('general.view_all')</a>
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
                                    <th>@lang('general.progress')</th>
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

                </div>
            </div>
            <!-- /.panel -->
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @lang('general.milestones')

                    @if ($user->is_admin)
                    <a href="{{ URL::to('/projects/'.$project->id.'/milestones') }}" class="btn btn-xs btn-primary pull-right">@lang('general.view_all')</a>
                    @else
                    @foreach ($project->managers as $manager)
                    @if ($manager->id == $user->id)
                    <a href="{{ URL::to('/projects/'.$project->id.'/milestones') }}" class="btn btn-xs btn-primary pull-right">@lang('general.view_all')</a>
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
                                    <td>{{$milestone->completed_at}}</td>
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
                    @lang('general.users')                    
                    @if ($user->is_admin)
                    <a href="{{ URL::to('/projects/'.$project->id.'/dashboard/edit') }}" class="btn btn-xs btn-primary pull-right">@lang('general.edit')</a>
                    @else
                    @foreach ($project->managers as $manager)
                    @if ($manager->id == $user->id)
                    <a href="{{ URL::to('/projects/'.$project->id.'/dashboard/edit') }}" class="btn btn-xs btn-primary pull-right">@lang('general.edit')</a>
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
                                    <td><a href="{{URL::to('/users/'.$user->id.'/profile')}}">{{$p_user->name}}</a></td>
                                    <td><a href="mailto:{{$user->email}}">{{$p_user->email}}</a></td>
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