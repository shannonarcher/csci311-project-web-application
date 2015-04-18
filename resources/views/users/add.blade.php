@extends('dashboard/master')

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
                    @lang('general.add_user')
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
                        @lang('general.details')                    
                        <button class="btn btn-xs btn-success pull-right" type="submit">@lang('general.save')</button>
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="form-group">
                                <label>@lang('general.name')</label>
                                <input class="form-control" type="text" placeholder="John Citizen" value="" name="name" />
                            </div>
                            <div class="form-group">
                                <label>@lang('general.email')</label>
                                <input class="form-control" type="text" placeholder="john.citizen@company.com" value="" name="email" />
                            </div>
                            <div class="form-group">
                                <label>@lang('general.lang')</label>
                                <select class="form-control" name="lang">
                                    <option value="en">English</option>
                                    <option value="jp">日本語</option>
                                </select>
                            </div>  
                            <div class="form-group">
                                <label>@lang('general.password')</label>
                                <input class="form-control" disabled placeholder="Password will be autogenerated by system." type="text" value="" name="password" />
                            </div>               
                            <div class="form-group">   
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_admin"> @lang('general.administrator')
                                    </label>
                                </div>
                                <div class="checkbox">
                                    <label>
                                        <input type="checkbox" name="is_archived"> @lang('general.archived')
                                    </label>
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