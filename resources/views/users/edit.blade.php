@extends('dashboard/master')

@section('stylesheets')
    <!-- DataTables CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">
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
				<h1>
                    {{$profile->name}}
                </h1>
			</div>
		</div>
	</div>
	<!-- details -->
	<div class="row">
		<div class="col-lg-6">
            <form role="form" id="details_form" action="" method="post">
     			<div class="panel panel-default">
                    <div class="panel-heading">
                        <i class="fa fa-book fa-fw"></i> @lang('general.details')                    
                        <button class="btn btn-xs btn-success pull-right" type="submit"><i class="fa fa-save fa-fw"></i> @lang('general.save')</button>
                    </div>
                    <div class="panel-body">
                    	<fieldset>
	                        <div class="form-group">
	                            <label>@lang('general.name')</label>
                                <input class="form-control" type="text" value="{{$profile->name}}" name="name" />
	                        </div>
                            <div class="form-group">
                                <label>@lang('general.email')</label>
                                <input class="form-control" type="text" value="{{$profile->email}}" name="email" />
                            </div>
                            <!--
                            <div class="form-group">
                                <label>@lang('general.lang')</label>
                                <select class="form-control" name="lang">
                                    <option value="en" {{ ($profile->lang == 'en' ? "selected" : "") }}>English</option>
                                    <option value="jp" {{ ($profile->lang == 'jp' ? "selected" : "") }}>日本語</option>
                                </select>
                            </div> 
                            -->
                            <div class="form-group">
                                <label>
                                    @lang('general.password') 
                                    <a href="#" class="btn btn-primary btn-xs" id="generate_rand_pass">@lang('general.generate_random')</a>
                                </label>
                                <input class="form-control" placeholder="@lang('general.enter_new_password_to_change')" type="text" value="" name="password" />
                            </div>                         
                            @if ($user->is_admin)
	                        <div class="form-group">   
	                            <div class="checkbox">
	                                <label>
	                                    <input type="checkbox" name="is_admin" {{$profile->is_admin ? 'checked' : ''}}> @lang('general.administrator')
	                                </label>
	                            </div>
	                            <div class="checkbox">
	                                <label>
	                                    <input type="checkbox" name="is_archived" {{$profile->is_archived ? 'checked' : ''}}> @lang('general.archived')
	                                </label>
	                            </div>
	                        </div>
                            @endif
	                    </fieldset>
    	            </div>
                </div>
            </form>
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
        $('#projects_table').DataTable({
                responsive: true
        });

        $('body').on('click', '#generate_rand_pass', function (e) {
            var chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789!#$%^&*()';
            var length = 8;
            var password = '';

            for (var i = 0; i < length; i++) {
                password += chars.charAt(Math.floor(Math.random()*chars.length));
            }

            $("[name=password]").val(password);
        });
    });
    </script>
@stop