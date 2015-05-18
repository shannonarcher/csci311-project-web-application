@extends('dashboard.master')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="page-header">
			<h1>{{$task->title}}</h1>
		</div>
	</div>
</div>
<!-- details -->
<div class="row">
	<div class="col-lg-12">
			<div class="panel panel-default">
	        <div class="panel-heading">
	            @lang('general.details')
                <a href="{{ URL::to('/projects/'.$task->project->id.'/tasks/'.$task->id.'/edit') }}" class="btn btn-xs btn-primary pull-right">@lang('general.edit')</a>
	        </div>
	        <div class="panel-body">
	            <form role="form">
	            	<fieldset disabled>
	            		<div class="col-md-6">
	                        <div class="form-group">
	                            <label>@lang('general.title')</label>
	                            <p class="form-control-static">{{$task->title}}</p>
	                        </div>
	                        <div class="form-group">
	                            <label>@lang('general.description')</label>
	                            <p class="form-control-static">{{$task->description}}</p>
	                        </div>
	                        <div class="form-group">
	                        	<label>@lang('general.project')</label>
	                        	<p class="form-control-static"><a href="{{URL::to('/projects/'.$task->project->id.'/dashboard')}}">{{$task->project->name}}</a></p>
	                        </div>
	                        <div class="form-group">
				    <label>@lang('general.progress')</label>
				    <p class="form-control-static">{{$task->progress}}%</p>
				</div>
	                   	</div>
	                   	<div class="col-md-6">
	                   		<div class="form-group">
	                   			<label>@lang('general.is_approved')</label>
	                   			<p class="form-control-static">{{ $task->approved_at != null ? trans('general.yes') : trans('general.no') }}</p>
	                   		</div>
	                   		<div class="form-group">
	                   			<label>@lang('general.start_date')</label>
	                   			<p class="form-control-static">{{$task->started_at}}</p>
	                   		</div>
	                   		<div class="form-group">
	                   			<label>@lang('general.estimation_duration')</label>
	                   			<p class="form-control-static">{{ ($task->estimation_duration / 86400) }}</p>
	                   		</div>
	                   		<div class="form-group">
	                   			<label>@lang('general.completed_at')</label>
	                   			<p class="form-control-static">
	                   				@if ($task->completed_at)
	                   				$task->completed_at
	                   				@else
	                   				@lang('general.in_progress')
	                   				@endif
	                   			</p>
	                   		</div>
	                   		@if ($task->parent)
	                   		<div class="form-group">
	                   			<label>@lang('general.super_task')</label>
	                   			<p class="form-control-static">
	                   				<a href="{{URL::to('/projects/'.$task->project->id.'/tasks/'.$task->parent->id)}}">@lang('general.task') {{$task->parent->id}}: {{$task->parent->title}}</a>
	                   			</p>
	                   		</div>
	                   		@endif
	                   		@if ($task->children)
	                   		<div class="form-group">
	                   			<label>@lang('general.sub_tasks')</label>
	                   			@foreach ($task->children as $child)		                   			
	                   			<p class="form-control-static">
	                   				<a href="{{URL::to('/projects/'.$task->project->id.'/tasks/'.$child->id)}}">@lang('general.task') {{$child->id}}: {{$child->title}}</a>
	                   			</p>
	                   			@endforeach
	                   		</div>
	                   		@endif
	                   		@if ($task->dependencies)
	                   		<div class="form-group">
	                   			<label>@lang('general.dependencies')</label>
	                   			@foreach ($task->dependencies as $dependent)		                   			
	                   			<p class="form-control-static">
	                   				<a href="{{URL::to('/projects/'.$task->project->id.'/tasks/'.$dependent->id)}}">@lang('general.task') {{$dependent->id}}: {{$dependent->title}}</a>
	                   			</p>
	                   			@endforeach
	                   		</div>
	                   		@endif
	                   	</div>
	                </fieldset>
	            </form>
	        </div>
	    </div>
	    <!-- /.panel -->
	</div>
	<div class="col-lg-12">
	        <div class="chat-panel panel panel-default">
	            <div class="panel-heading">
	                <i class="fa fa-comments fa-fw"></i>
	                @lang('general.chat')
	                <div class="btn-group pull-right">
	                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
	                        <i class="fa fa-chevron-down"></i>
	                    </button>
	                    <ul class="dropdown-menu slidedown">
	                        <li>
	                            <a href="{{ URL::to('/projects/'.$task->project->id.'/tasks/'.$task->id) }}">
	                                <i class="fa fa-refresh fa-fw"></i> @lang('general.refresh')
	                            </a>
	                        </li>
	                    </ul>
	                </div>
	            </div>
	            <!-- /.panel-heading -->
	            <div class="panel-body">
	                <ul class="chat" id="chat_log">
	                	@foreach ($task->comments as $index => $comment)
	                	<li class="@if($index % 2 == 0) left @else right @endif clear-fix">	                		
	                        <span class="chat-img @if($index % 2 == 0) pull-left @else pull-right @endif">
	                            <img src="http://placehold.it/50/55C1E7/fff" alt="User Avatar" class="img-circle" />
	                        </span>
	                        <div class="chat-body clearfix">
	                            <div class="header">
	                            	@if($index % 2 == 0)
                                    <strong class="primary-font"><a href="{{ URL::to('/users/'.$comment->created_by->id.'/profile') }}">{{$comment->created_by->name}}</a></strong>
                                    <small class="pull-right text-muted">
                                        <i class="fa fa-clock-o fa-fw"></i> {{ $comment->updated_at }}</small>
	                            	@else 
	                            	<small class=" text-muted">
                                        <i class="fa fa-clock-o fa-fw"></i> {{ $comment->updated_at }}</small>
                                    <strong class="pull-right primary-font"><a href="{{ URL::to('/users/'.$comment->created_by->id.'/profile') }}">{{$comment->created_by->name}}</a></strong>
	                            	@endif
	                            </div>
	                            <p>
	                                {{ $comment->comment }}
	                            </p>
	                        </div>
	                	</li>
	                	@endforeach
	                </ul>
	            </div>
	            <!-- /.panel-body -->
	            <div class="panel-footer">
	                <div class="input-group">
	                    <input id="chat_box" type="text" class="form-control input-sm" placeholder="@lang('general.type_your_message_here')" />
	                    <span class="input-group-btn">
	                        <button class="btn btn-warning btn-sm" id="chat_btn">
	                            @lang('general.send')
	                        </button>
	                    </span>
	                </div>
	            </div>
	            <!-- /.panel-footer -->
	        </div>
	        <!-- /.panel .chat-panel -->
	    </div>
	</div>
</div>
@stop

@section('scripts')
<script>
	(function () {
		$('body').on('keyup', '#chat_box', function (e) {
			if (e.keyCode == 13)
				submitComment();
		});

		$('body').on('click', '#chat_btn', submitComment);

		function submitComment() {
			var text = $("#chat_box").val();
			$("#chat_box").val('');

			$.ajax({
				url:'{{ URL::to("/ajax/tasks/$task->id/comments") }}',
				method:'POST',
				data: {
					text: text
				}
			}).success(function (e) {
				updateComments(e);
			});
		}

		function updateComments(comments) {
			var html = "";
			for (var i = 0; i < comments.length; i++) {
				html += '<li class="' + (i % 2 == 0 ? 'left':'right') + ' clear-fix">' +                		
	                        '<span class="chat-img ';

	            if (i % 2 == 0)
	            	html += 'pull-left ';
	            else
	            	html += 'pull-right';

	            html += '"><img src="http://placehold.it/50/55C1E7/fff" alt="User Avatar" class="img-circle" /></span>' +
	            		'<div class="chat-body clearfix">' +
	            		'<div class="header">';

	            if (i % 2 == 0) {
	            	html += '<strong class="primary-font"><a href="{{ URL::to('/users/') }}' + comments[i].created_by.id + '/profile">' + 
	            			comments[i].created_by.name + '</a></strong><small class="pull-right text-muted">' +
	            			'<i class="fa fa-clock-o fa-fw"></i> ' + comments[i].updated_at + '</small>';

	            } else {
	            	html += '<small class="text-muted"><i class="fa fa-clock-o fa-fw"></i> ' + comments[i].updated_at + '</small>' +
	            	        '<strong class="pull-right primary-font"><a href="{{ URL::to('/users/') }}' + comments[i].created_by.id + '/profile">' +
	            	        comments[i].created_by.name + '</a></strong>'
	           	}

	           	html += '</div><p>' + comments[i].comment + '</p></div></li>';
			}
			$("#chat_log").html(html);
		}

		function pollComments() {
			$.ajax({
				url:'{{ URL::to("/ajax/tasks/$task->id/comments") }}',
				method:'GET'
			}).success(function (e) {
				updateComments(e);
			});
		}

		/*setInterval(pollComments, 5000);*/
	})();
</script>
@stop
