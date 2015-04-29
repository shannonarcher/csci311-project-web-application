<!DOCTYPE html>
<head>
  <meta http-equiv="Content-type" content="text/html; charset=utf-8">
  <title>@lang('menu.app_title')</title>
</head>
  <script src="{{ URL::to('/') }}/codebase/dhtmlxgantt.js" type="text/javascript" charset="utf-8"></script>
  <link rel="stylesheet" href="{{ URL::to('/') }}/codebase/dhtmlxgantt.css" type="text/css" media="screen" title="no title" charset="utf-8">

  <style type="text/css">
    html, body{ height:100%; padding:0px; margin:0px; overflow: hidden;}
  </style>
<body>
  <div id="gantt_here" style='width:100%; height:100%;'></div>
  <script type="text/javascript">
    var tasks =  <?php echo json_encode($tasks) ?>;

    gantt.init("gantt_here");


    gantt.parse(tasks);

  </script>
</body>