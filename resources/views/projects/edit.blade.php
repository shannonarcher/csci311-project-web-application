@extends("dashboard.master")

@section('content')
	<div class="row">
		<div class="col-lg-12">
			<div class="page-header">
				<h1>{{ $project->name }}</h1>
			</div>
		</div>
	</div>
	<!-- details -->
	<div class="row">
		<div class="col-lg-12">
            <form role="form" method="post">
     			<div class="panel panel-default">
                    <div class="panel-heading">
                        Details
                        <button class="btn btn-xs btn-success pull-right" type="submit">Save</button>
                    </div>
                    <div class="panel-body">
                    	<fieldset>
                    		<div class="col-md-6">
		                        <div class="form-group">
		                            <label>Name</label>
		                            <input type="text" name="name" value="{{ $project->name }}" placeholder="" class="form-control" />
		                        </div>	   
                            </div>
                            <div class="col-md-6">                 	
		                        <div class="form-group">
		                            <label>Start Date</label>
		                            <input type="text" name="started_at" value="{{ $project->started_at }}" placeholder="" class="form-control" />
		                        </div>
                                <div class="form-group">
                                    <label>Expected Completion Date</label>
                                    <input type="text" name="expected_completed_at" value="{{ $project->expected_completed_at }}" placeholder="" class="form-control" />
                                </div>
                                <div class="form-group">
                                    <label>Actual Completion Date</label>
                                    <input type="text" name="actual_completed_at" value="{{ $project->actual_completed_at }}" placeholder="In Progress" class="form-control" />
                                </div>
		                    </div>
	                    </fieldset>
    	            </div>
                </form>
	        </div>
            <!-- /.panel -->
		</div>
	</div>
	<!-- projects -->
@stop