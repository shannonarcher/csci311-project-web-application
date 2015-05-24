<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>@lang('menu.app_title')</title>


    <!-- Bootstrap Core CSS -->
    <link href="{{ URL::to('/') }}/bower_components/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    
    @yield('stylesheets')

    <!-- MetisMenu CSS -->
    <link href="{{ URL::to('/') }}/bower_components/metisMenu/dist/metisMenu.min.css" rel="stylesheet">

    <!-- Timeline CSS -->
    <link href="{{ URL::to('/') }}/dist/css/timeline.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="{{ URL::to('/') }}/dist/css/sb-admin-2.css" rel="stylesheet">

    <!-- Morris Charts CSS -->
    <link href="{{ URL::to('/') }}/bower_components/morrisjs/morris.css" rel="stylesheet">

    <!-- Custom Fonts -->
    <link href="{{ URL::to('/') }}/bower_components/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">

    <link href="{{ URL::to('/') }}/dist/css/sweetalert.css" rel="stylesheet" type="text/css">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>

<body>

    <div id="wrapper">

        <!-- Navigation -->
        <nav class="navbar navbar-default navbar-static-top" role="navigation" style="margin-bottom: 0">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ URL::to('/dashboard') }}"> @lang('menu.app_title')</a>
            </div>
            <!-- /.navbar-header -->

            <ul class="nav navbar-top-links navbar-right">
                <!-- /.dropdown -->
                <li class="dropdown">
                    <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                        <i class="fa fa-user fa-fw"></i> @lang('general.welcome'), {{ explode(' ', $user->name)[0] }} <i class="fa fa-caret-down"></i>
                    </a>
                    <ul class="dropdown-menu dropdown-user">
                        <li><a href="{{ URL::to('/users/'.$user->id.'/profile') }}"><i class="fa fa-user fa-fw"></i> @lang('menu.my_profile')</a>
                        </li>
                        </li>
                        <li class="divider"></li>
                        <li><a href="{{ URL::to('/logout') }}"><i class="fa fa-sign-out fa-fw"></i> @lang('menu.logout')</a>
                        </li>
                    </ul>
                    <!-- /.dropdown-user -->
                </li>
                <!-- /.dropdown -->
            </ul>
            <!-- /.navbar-top-links -->

            <div class="navbar-default sidebar" role="navigation">
                <div class="sidebar-nav navbar-collapse">
                    <ul class="nav" id="side-menu">
                        <li>
                            <a href="{{ URL::to('/dashboard') }}"><i class="fa fa-dashboard fa-fw"></i> @lang('menu.dashboard')</a>
                        </li>
                        <li>
                            <a href="{{ URL::to('/users') }}"><i class="fa fa-users fa-fw"></i> @lang('menu.users')</a>
                        </li>
                        <li>
                            <a href="#">
                                <i class="fa fa-cube fa-fw"></i> 
                                @lang('menu.projects')
                                <span class="fa arrow"></span>
                            </a> 
                            <ul class="nav nav-second-level collapse in">
                                <li><a href="{{ URL::to('/projects') }}">@lang('menu.all_projects')</a></li>
                                @foreach ($user->projects as $project) 
                                <li><a href="{{ URL::to('/projects/'.$project->id.'/dashboard') }}">{{ $project->name }}</a> </li>
                                @endforeach
                            </ul>
                        </li>
                    </ul>
                </div>
                <!-- /.sidebar-collapse -->
            </div>
            <!-- /.navbar-static-side -->
        </nav>

        <div id="page-wrapper">
            @yield('content')
        </div>
        <!-- /#page-wrapper -->

    </div>
    <!-- /#wrapper -->

    <!-- jQuery -->
    <script src="{{ URL::to('/') }}/bower_components/jquery/dist/jquery.min.js"></script>

    <!-- Bootstrap Core JavaScript -->
    <script src="{{ URL::to('/') }}/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>

    <!-- Metis Menu Plugin JavaScript -->
    <script src="{{ URL::to('/') }}/bower_components/metisMenu/dist/metisMenu.min.js"></script>

    <!-- Custom Theme JavaScript -->
    <script src="{{ URL::to('/') }}/dist/js/sb-admin-2.js"></script>

    <script src="{{ URL::to('/') }}/dist/js/sweetalert.min.js"></script>


    @yield('scripts')

</body>

</html>
