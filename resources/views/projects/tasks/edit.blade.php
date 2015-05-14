@extends('dashboard.master')

@section('stylesheets')
    <!-- DataTables CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
@stop

@section('content')
<div class="row">
    @if (isset($message))
    <div class="col-lg-12" style="padding-top:30px;">
        <div class="alert alert-success">{{$message}}</div>
    </div>
    @endif
	<div class="col-lg-12">
		<div class="page-header">
			<h1>{{ $task->title }}</h1>
		</div>
	</div>
</div>
<!-- details -->
<div class="row">
	<div class="col-lg-12">
	    <form role="form" method="post" action="{{ URL::to('/projects/'.$task->project_id.'/tasks/'.$task->id.'/save') }}">
			<div class="panel panel-default">
		        <div class="panel-heading">
		            @lang('general.details')
		            <button class="btn btn-xs btn-success pull-right" type="submit">@lang('general.save')</button>
		        </div>
		        <div class="panel-body">
	            	<fieldset>
	            		<div class="col-md-6">
	                        <div class="form-group">
	                            <label>@lang('general.title')</label>
	                            <input type="text" name="title" class="form-control" value="{{ $task->title }}" />
	                        </div>
	                        <div class="form-group">
	                            <label>@lang('general.description')</label>
	                            <textarea class="form-control" name="description">{{ $task->description }}</textarea>
	                        </div>
                            <div class="form-group">
                                <label>@lang('general.is_approved')</label>
                                <input type="checkbox" class="checkbox" name="is_approved" {{ $task->approved_at == null ? '' : 'checked' }} />
                            </div>
	                   	</div>
	                   	<div class="col-md-6">
	                   		<div class="form-group">
	                   			<label>@lang('general.start_date')</label>
	                   			<input type="text" name="started_at" class="form-control" value="{{ $task->started_at }}" />
	                   		</div>
	                   		<div class="form-group">
	                   			<label>@lang('general.estimation_duration') (@lang('general.days'))</label>
	                   			<input type="text" name="estimation_duration" class="form-control" value="{{ $task->estimation_duration / 86400 }}" />
	                   		</div>
	                   		<div class="form-group">
	                   			<label>@lang('general.super_task')</label>
	                   			<p class="form-control-static" id="parent"></p>
	                   		</div>
	                   		<div class="form-group">
	                   			<label>@lang('general.dependencies')</label>
	                   			<p class="form-control-static" id="dependencies"></p>
	                   		</div>
	                   	</div>
	                </fieldset>
		        </div>
	    	</div>
		</form>
	    <!-- /.panel -->
	</div>
	<div class="col-lg-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				@lang('general.tasks')
			</div>
			<div class="panel-body">
	            <div class="dataTable_wrapper">
                    <table class="table table-striped table-bordered table-hover" id="task_table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>@lang('general.title')</th>
                                <th>@lang('general.actions')</th>
                            </tr>
                        </thead>
                        <tbody>
                        	@foreach ($tasks as $t_task)
                            <tr>
                                <td>{{$t_task->id}}</td>
                                <td><a href="{{URL::to('/projects/'.$t_task->project_id.'/tasks/'.$t_task->id)}}">{{$t_task->title}}</a></td>
                                <td>
                                	<div class="btn-group btn-group-xs" data-task="{{json_encode($t_task)}}">
                                		<a class="btn btn-xs @if($t_task->id == $task->parent_id) btn-success @else btn-default @endif" data-action="set_parent">@lang('general.parent')</a>
                                		<a class="btn btn-xs btn-default 
                                            @foreach($task->dependencies as $dep) 
                                            @if ($dep->id == $t_task->id)
                                            hide
                                            @endif  
                                            @endforeach" data-action="add_dependency">@lang('general.add_dependency')</a>
                                		<a class="btn btn-xs btn-danger hide 
                                            @foreach($task->dependencies as $dep) 
                                            @if ($dep->id == $t_task->id)
                                            show
                                            @endif  
                                            @endforeach" data-action="remove_dependency">@lang('general.remove_dependency')</a>
                                	</div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
			</div>
		</div>
	</div>
</div>
@stop


@section('scripts')
    <!-- DataTables JavaScript -->
    <script src="{{ URL::to('/') }}/bower_components/datatables/media/js/jquery.dataTables.min.js"></script>
    <script src="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
    	var none_trans = "@lang('general.none')";

    	var parent = JSON.parse('<?php echo json_encode($task->parent) ?>');
    	var dependencies = JSON.parse('<?php echo json_encode($task->dependencies) ?>');

        $('#task_table').DataTable({
                responsive: true
        });

        $('body').on('click', '[data-action=set_parent]', function (e) {
        	var task = JSON.parse($(this).parent().attr('data-task'));
        	parent = task;

        	$('[data-action=set_parent]').removeClass('btn-success').addClass('btn-default');
        	$(this).removeClass('btn-default').addClass('btn-success');

        	updateParentAndDependencies();
        });

        $('body').on('click', '[data-action=add_dependency]', function (e) {
        	var task = JSON.parse($(this).parent().attr('data-task'));
        	dependencies.push(task);

        	$(this).siblings('[data-action=remove_dependency]').removeClass('hide');
        	$(this).addClass('hide').removeClass('show');

        	updateParentAndDependencies();
        });

        $('body').on('click', '[data-action=remove_dependency]', function (e) {
        	var task = JSON.parse($(this).parent().attr('data-task'));
        	for (var i = 0; i < dependencies.length; i++) {
        		if (dependencies[i].id == task.id) {
        			dependencies.splice(i, 1);
        			break;
        		}
        	}

        	$(this).siblings('[data-action=add_dependency]').removeClass('hide');
        	$(this).addClass('hide').removeClass('show');

        	updateParentAndDependencies();
        });

        function updateParentAndDependencies() {
        	var parent_html = "";
        	if (parent != null)
        		parent_html = parent.title + "<input type='hidden' name='parent' value='" + parent.id + "' />";
        	else 
        		parent_html = none_trans;
        	$("#parent").html(parent_html);

        	var dependency_html = "";
        	for (var i = 0; i < dependencies.length; i++) {
        		dependency_html += dependencies[i].title + ", <input type='hidden' name='dependencies[" + i + "]' value='" + dependencies[i].id + "' />";
        	}
        	if (dependencies.length <= 0)
        		dependency_html = none_trans;
        	$("#dependencies").html(dependency_html);
        }
        updateParentAndDependencies();
    });
    </script>
@stop