@extends('dashboard.master')

@section('stylesheets')
    <!-- Datepicker CSS -->
    <link href="{{ URL::to('/') }}/css/datepicker.min.css" rel="stylesheet" />
@stop

@section('content')
<div class="row">
	<div class="col-lg-12">
		<div class="page-header">
			@if (isset($error_message))
			<div class="alert alert-danger">
			{{$error_message}}
			</div>
			@endif
			<h1>@lang('general.add_milestone')</h1>
		</div>
	</div>
</div>
<!-- details -->
<div class="row">
	<div class="col-lg-12">
	    <form role="form" method="post" action="{{ URL::to('/projects/'.$project_id.'/milestones/create') }}">
			<div class="panel panel-default">
		        <div class="panel-heading">
		            <i class="fa fa-book fa-fw"></i> @lang('general.details')
		            <button class="btn btn-xs btn-success pull-right" type="submit"><i class="fa fa-save fa-fw"></i> @lang('general.save')</button>
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
	                            <input data-datepicker type="text" name="completed_at" class="form-control" />
	                        </div>
	                   	</div>
	                </fieldset>
		        </div>
	    	</div>
		</form>
	    <!-- /.panel -->
	</div>
</div>
@stop\



@section('scripts')
    <!-- DataTables JavaScript -->
    <script src="{{ URL::to('/') }}/js/datepicker.min.js"></script>

    <!-- Page-Level Demo Scripts - Tables - Use for reference -->
    <script>
    $(document).ready(function() {
        // some datepicker 
        $("[data-datepicker]").datepicker({
            format:'yyyy-mm-dd'
        });
    });
    </script>
@stop