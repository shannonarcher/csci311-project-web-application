@extends("dashboard.master")

@section('content')
	<div class="row">
		<div class="col-lg-12">  
			<div class="page-header">
				<h1>
                    {{$project->name}} @lang('general.pert')
                    <a href='{{ URL::to("/projects/$project->id/dashboard") }}' class="btn btn-sm btn-default"><i class="fa fa-cube fa-fw"></i> @lang('general.dashboard')</a>
                </h1>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
 			<div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-calculator fa-fw"></i> @lang('general.pert_analysis')             
                </div>
                <div class="panel-body">
                    <div class="">
                        <p>Standard Deviation (All): {{ (round($all_std_dev * 1000) / 1000) }}</p>
                        <p>Target Time: {{ (round($target * 1000) / 1000) }} days.</p>
                        <p>Z value: {{ round($z_value * 1000) / 1000 }}</p>
                        <p>{{ $chance }}% chance of completing in {{ (round($target * 1000) / 1000) }} days.</p>
                    </div>

		            <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="task_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('general.title')</th>
                                    <th>@lang('general.optimistic_duration')</th>
                                    <th>@lang('general.estimation_duration')</th>
                                    <th>@lang('general.pessimistic_duration')</th>
                                    <th>@lang('general.expected_time')</th>
                                    <th>@lang('general.standard_deviation')</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach ($tasks as $task)
                                <tr>
                                    <td>{{$task->id}}</td>
                                    <td><a href="{{URL::to('/projects/'.$project->id.'/tasks/'.$task->id)}}">{{$task->title}}</a></td>
                                    <td>{{ $task->optimistic_duration / 86400 }}</td>
                                    <td>{{ $task->estimation_duration / 86400 }}</td>
                                    <td>{{ $task->pessimistic_duration / 86400 }}</td>
                                    <td>{{ round($task->expected_time * 1000) / 1000 }}</td>
                                    <td>{{ round($task->std_dev * 1000) / 1000 }}</td>
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