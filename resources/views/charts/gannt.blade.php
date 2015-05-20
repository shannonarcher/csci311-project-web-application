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
    var tasks =  <?php echo str_replace('"milestone"', "gantt.config.types.milestone", json_encode($tasks)); ?>;

    gantt.config.lightbox.sections = [
        {name: "description", height: 70, map_to: "text", type: "textarea"},
        {name: "type", type: "typeselect", map_to: "type"},
        {name: "time", height: 72, type: "duration", map_to: "auto"}
    ];

    /*gantt.templates.rightside_text = function(start, end, task){
        if(task.type == gantt.config.types.milestone){
            return task.text;
        }
        return "";
    };*/

    gantt.templates.task_class = function (start, end, task) {
      if (task.type == gantt.config.types.milestone) {
        return "gantt_milestone";
      }
      return "";
    };

    gantt.config.readonly = true;

    gantt.init("gantt_here");

    gantt.parse(tasks);

  </script>
</body>