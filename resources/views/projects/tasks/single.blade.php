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
	            Details
	        </div>
	        <div class="panel-body">
	            <form role="form">
	            	<fieldset disabled>
	            		<div class="col-md-6">
	                        <div class="form-group">
	                            <label>Title</label>
	                            <p class="form-control-static">{{$task->title}}</p>
	                        </div>
	                        <div class="form-group">
	                            <label>Description</label>
	                            <p class="form-control-static">{{$task->description}}</p>
	                        </div>
	                        <div class="form-group">
	                        	<label>Project</label>
	                        	<p class="form-control-static"><a href="{{URL::to('/projects/'.$task->project->id.'/dashboard')}}">{{$task->project->name}}</a></p>
	                        </div>
	                   	</div>
	                   	<div class="col-md-6">
	                   		<div class="form-group">
	                   			<label>Start Date</label>
	                   			<p class="form-control-static">{{$task->started_at}}</p>
	                   		</div>
	                   		<div class="form-group">
	                   			<label>Estimation Duration</label>
	                   			<p class="form-control-static">{{$task->estimation_duration}}</p>
	                   		</div>
	                   		<div class="form-group">
	                   			<label>Completed at</label>
	                   			<p class="form-control-static">
	                   				@if ($task->completed_at)
	                   				$task->completed_at
	                   				@else
	                   				In Progress
	                   				@endif
	                   			</p>
	                   		</div>
	                   		@if ($task->parent)
	                   		<div class="form-group">
	                   			<label>Super Task</label>
	                   			<p class="form-control-static">
	                   				<a href="{{URL::to('/projects/'.$task->project->id.'/tasks/'.$task->parent->id)}}">Task {{$task->parent->id}}: {{$task->parent->title}}</a>
	                   			</p>
	                   		</div>
	                   		@endif
	                   		@if ($task->children)
	                   		<div class="form-group">
	                   			<label>Sub Tasks</label>
	                   			@foreach ($task->children as $child)		                   			
	                   			<p class="form-control-static">
	                   				<a href="{{URL::to('/projects/'.$task->project->id.'/tasks/'.$child->id)}}">Task {{$child->id}}: {{$child->title}}</a>
	                   			</p>
	                   			@endforeach
	                   		</div>
	                   		@endif
	                   		@if ($task->dependencies)
	                   		<div class="form-group">
	                   			<label>Dependencies</label>
	                   			@foreach ($task->dependencies as $dependent)		                   			
	                   			<p class="form-control-static">
	                   				<a href="{{URL::to('/projects/'.$task->project->id.'/tasks/'.$dependent->id)}}">Task {{$dependent->id}}: {{$dependent->title}}</a>
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
	                Chat
	                <div class="btn-group pull-right">
	                    <button type="button" class="btn btn-default btn-xs dropdown-toggle" data-toggle="dropdown">
	                        <i class="fa fa-chevron-down"></i>
	                    </button>
	                    <ul class="dropdown-menu slidedown">
	                        <li>
	                            <a href="{{ URL::to('/projects/'.$task->project->id.'/tasks/'.$task->id) }}">
	                                <i class="fa fa-refresh fa-fw"></i> Refresh
	                            </a>
	                        </li>
	                    </ul>
	                </div>
	            </div>
	            <!-- /.panel-heading -->
	            <div class="panel-body">
	                <ul class="chat">
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
	                    <input id="btn-input" type="text" class="form-control input-sm" placeholder="Type your message here..." />
	                    <span class="input-group-btn">
	                        <button class="btn btn-warning btn-sm" id="btn-chat">
	                            Send
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