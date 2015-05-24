@extends('dashboard/master')

@section('stylesheets')
    <!-- DataTables CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-plugins/integration/bootstrap/3/dataTables.bootstrap.css" rel="stylesheet">

    <!-- DataTables Responsive CSS -->
    <link href="{{ URL::to('/') }}/bower_components/datatables-responsive/css/dataTables.responsive.css" rel="stylesheet">

    <style>
        .search_results {
            position:relative;
        }

        #skill_search_results {
            list-style: none;
            padding:0;
            margin:0;
            position:absolute;
            z-index:100;
            width:100%;
            background:#fff;
            border-left:solid 1px #eee;
            border-right:solid 1px #eee;
            max-height:200px;
            overflow-y: scroll;
        }

        #skill_search_results li {
            padding:3px 2.5%;
            line-height:30px;
            cursor:pointer;
            background:#fff;
        }

        #skill_search_results li:first-child {
            border-top:solid 1px #eee;
            background:#f8f8f8;
        }

        #skill_search_results li:last-child {
            border-bottom:solid 1px #eee;
        }
    </style>
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
                @if (isset($error_message))
                <div class="alert alert-danger">
                {{$error_message}}
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
 			<div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-book fa-fw"></i> @lang('general.details')                    
                    @if ($profile->id == $user->id || $user->is_admin)
                    <a class="btn btn-xs btn-primary pull-right" href="{{URL::to('/users/'.$profile->id.'/profile/edit')}}"><i class="fa fa-pencil fa-fw"></i> @lang('general.edit')</a>
                    @endif
                </div>
                <div class="panel-body">
                    <form role="form">
                    	<fieldset disabled>
	                        <div class="form-group">
	                            <label>@lang('general.name')</label>
	                            <p class="form-control-static">{{$profile->name}}</p>
	                        </div>
	                        <div class="form-group">
	                            <label>@lang('general.email')</label>
	                            <a href="mailto:{{$profile->email}}" class="form-control-static">{{$profile->email}}</a>
	                        </div>
	                        <div class="form-group">
	                            <div class="checkbox">
	                                <label>
	                                    <input type="checkbox" value="" {{$profile->is_admin ? 'checked' : ''}}> @lang('general.administrator')
	                                </label>
	                            </div>
	                            <div class="checkbox">
	                                <label>
	                                    <input type="checkbox" value="" {{$profile->is_archived ? 'checked' : ''}}> @lang('general.archived')
	                                </label>
	                            </div>
	                        </div>
	                    </fieldset>
	                </form>
	            </div>
	        </div>
            <!-- /.panel -->
		</div>
        <div class="col-lg-6">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-magic fa-fw"></i> @lang('general.roles')  
                </div>
                <div class="panel-body">                    
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="roles_table">
                            <thead>
                                <tr>
                                    <th>@lang('general.name')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($profile->roles as $role)
                                <tr>
                                    <td>{{ $role->name }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-gamepad fa-fw"></i> @lang('general.skills')
                </div>
                <div class="panel-body">
                    @if ($profile->id == $user->id || $user->is_admin)
                    <form>
                        <div class="form-group">
                            <input class="form-control" type="text" name="skill_name" placeholder="Search for new skill..." id="skill_search" />
                            <div class="search_results">
                                <ul id="skill_search_results">

                                </ul>
                            </div>
                        </div>
                    </form>
                    @endif
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="skills_table">
                            <thead>
                                <tr>
                                    <th>@lang('general.name')</th>
                                    @if ($profile->id == $user->id || $user->is_admin)
                                    <th>@lang('general.actions')</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($profile->skills as $skill)
                                <tr>
                                    <td>{{ $skill->name }}</td>
                                    @if ($profile->id == $user->id || $user->is_admin)
                                    <td>
                                        <a data-id="{{ $skill->id }}" data-action="remove_skill" class="btn btn-xs btn-danger">Remove</a>
                                    </td>
                                    @endif
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
		<div class="col-lg-12">
 			<div class="panel panel-default">
                <div class="panel-heading">
                    <i class="fa fa-cube fa-fw"></i> @lang('general.projects')
                </div>
                <div class="panel-body">
                    <div class="dataTable_wrapper">
                        <table class="table table-striped table-bordered table-hover" id="projects_table">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>@lang('general.name')</th>
                                    <th>@lang('general.is_manager')</th>
                                </tr>
                            </thead>
                            <tbody>
                            	@foreach ($profile->projects as $project)
                                <tr>
                                    <td>{{$project->id}}</td>
                                    <td><a href="{{URL::to('/projects/'.$project->id.'/dashboard')}}">{{$project->name}}</a></td>
                                    <td>
                                    	{{$project->pivot->is_manager ? trans("general.yes") : trans("general.no") }}
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
	            </div>
	        </div>
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
        var skills = [];

        $('#projects_table, #skills_table, #roles_table').DataTable({
                responsive: true
        });

        $('body').on('change, keyup', '#skill_search', function () {
            var search_val = $(this).val().toLowerCase().trim();
            var result_html  = "";

            if (search_val.length > 0) {
                for (var i = 0; i < skills.length; i++) {
                    if (skills[i].name.toLowerCase().indexOf(search_val) > -1) {
                        result_html += "<li class='skill_list_item' data-id='" + skills[i].id + "'>" + skills[i].name + "</li>";
                    }
                }

                result_html = "<li class='skill_list_item' data-id='0' class='search_term'>Add Skill: " + $(this).val() + "</li>" + result_html;
            }

            $("#skill_search_results").html(result_html);
        });

        $('body').on('click', '.skill_list_item', function () {
            var name = $(this).text();
            name = name.replace("Add Skill:", "").trim();

            $.ajax({
                url:"{{ URL::to('/ajax/users/'.$profile->id.'/skills') }}",
                method:"POST",
                data: {
                    id: $(this).attr('data-id'),
                    name: name
                },
                content:"application-json"
            }).success(function (e) {
                $('#skill_search').val('');
                $('#skill_search_results').html('');

                swal("Success!", "Added skill to user.", "success");

                updateSkillsList(e);
            });
        });

        $('body').on('click', '[data-action=remove_skill]', function () {
            var id = $(this).attr('data-id');
           
            $.ajax({
                url:"{{ URL::to('/ajax/users/'.$profile->id.'/skills/') }}",
                method:"POST",
                data: {
                    _method:"DELETE",
                    id:id
                }
            }).success(function (e) {
                swal("Success!", "Removed skill from user.", "success");

                updateSkillsList(e);
            }).failure(function (e) {
                swal("Failure", "You can't do that.", "failure");
            });
        });

        function updateSkillsList(e) {
            e.sort(function (a,b) {
                if (a.name > b.name) 
                    return 1;
                else if (a.name < b.name)
                    return -1;
                return 0;
            });

            var dataSet = [];
            for (var i = 0; i < e.length; i++) {
                dataSet.push([e[i].name, 
                    "<a data-id='" + e[i].id + "' data-action='remove_skill' class='btn btn-xs btn-danger'>Remove</a>"]);
            }

            var table = $("#skills_table").DataTable();
            table.destroy();

            table = $("#skills_table").DataTable({
                responsive: true,
                data: dataSet,
                columns: [ {"title":"@lang('general.name')" }, 
                           {"title":"@lang('general.actions')"} ]
            });

            updateAllSkillsList();
        }

        function updateAllSkillsList() {            
            $.ajax({
                url:"{{ URL::to('/ajax/skills') }}",
                content:"application-json",
                method:"GET"
                }).success(function (e) {
                    skills = e;
                    skills.sort(function (a,b) {
                        if (a.name > b.name)
                            return 1;
                        else if (a.name < b.name)
                            return -1;
                        return 0;
                    });
                });
        }

        updateAllSkillsList();
    });
    </script>
@stop