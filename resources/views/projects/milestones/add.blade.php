@extends('dashboard.master')

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="page-header">
			<h1>@lang('general.milestones')</h1>
		</div>
	</div>
</div>
<!-- details -->
<div class="row">
	<div class="col-lg-12">
	    <form role="form" method="post" action="{{ URL::to('/projects/'.$project_id.'/milestones/create') }}">
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
	                            <input type="text" name="title" class="form-control" />
	                        </div>
	                        <div class="form-group">
	                            <label>@lang('general.completed_at')</label>
	                            <input type="text" name="completed_at" class="form-control" />
	                        </div>
	                   	</div>
	                </fieldset>
		        </div>
	    	</div>
		</form>
	    <!-- /.panel -->
	</div>
</div>
@stop