@extends("dashboard.master")

@section('content')
  <div class="row">
    <div class="col-lg-12">  
      <div class="page-header">
        <h1>
            {{$project->name}} @lang('general.critical_chain')
            <a href='{{ URL::to("/projects/$project->id/dashboard") }}' class="btn btn-sm btn-default"><i class="fa fa-cube fa-fw"></i> @lang('general.dashboard')</a>
        </h1>
      </div>
    </div>
  </div>
  <div class="row">
    <div class="col-lg-12">
      <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-calculator fa-fw"></i> @lang('general.critical_chain_buffer_analysis')             
                </div>
                <div class="panel-body">
                    <div class="">
                        <p>Project Buffer: {{ $project_buffer }} days.</p>
                    </div>

                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="task_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('general.title')</th>
                                    <th>@lang('general.most_likely')</th>
                                    <th>@lang('general.plus_comfort_zone')</th>
                                    <th>@lang('general.comfort_zone')</th>
                                </tr>
                            </thead>
                            <tbody>
                              @foreach ($tasks as $task)
                                <tr>
                                    <td>{{$task->id}}</td>
                                    <td><a href="{{URL::to('/projects/'.$project->id.'/tasks/'.$task->id)}}">{{$task->title}}</a></td>
                                    <td>{{ round($task->estimation_duration / 86400 * 100) / 100 }}</td>
                                    <td>{{ round($task->pessimistic_duration / 86400 * 100) / 100 }}</td>
                                    <td>{{ round(($task->pessimistic_duration - $task->estimation_duration) / 86400 * 100) / 100 }}</td>
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