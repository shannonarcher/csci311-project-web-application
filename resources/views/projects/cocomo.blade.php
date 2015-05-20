@extends("dashboard.master")

@section('content')        
    @if (isset($message))
    <div class="row" style="padding-top:30px;">
        <div class="col-lg-12">    
            <div class="alert alert-success">
            {{$message}}
            </div>
        </div>
    </div>
    @endif
    <div class="row">
        <div class="col-lg-12">
            <div class="page-header">
                <h1>{{ $project->name }}
                    <small>
                        <a href='{{ URL::to("/projects/$project->id/dashboard") }}' class="btn btn-sm btn-default">@lang('general.dashboard')</a>
                    </small>
                </h1>
            </div>
        </div>
    </div>
    <!-- details -->
    <div class="row">
        <div class="col-lg-12">
            <form role="form" method="post">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        @lang('general.cocomo')
                        <button class="btn btn-xs btn-success pull-right" type="submit">@lang('general.save')</button>
                    </div>
                    <div class="panel-body">
                        <fieldset>
                            <div class="row">
                                <div class="col-md-4 col-xs-12">
                                    <div class="form-group">
                                        <label>Thousands of Lines of Code (KLOC)</label>
                                        <input type="text" class="form-control" name="kloc" value="{{ $project->kloc }}" />
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-lg-4 col-md-6 col-sm-12">
                                    <h2>@lang('general.cocomoI') <small><span class="cocomo1">0</span> person months</small></h2>
                                    <div class="form-group">
                                        <label>System Type</label>
                                        <select class="form-control" name="system_type">
                                            @foreach ($types as $type)
                                            <option @if ($type->id == $project->system_type_id) selected @endif value="{{ $type->id }}" data-c="{{ $type->c }}" data-k="{{ $type->k }}">{{ $type->name }}</option>
                                            @endforeach
                                        </select>

                                    </div>
                                    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                      <div class="panel panel-default">
                                        <div class="panel-heading" role="tab" id="headingOne">
                                          <h4 class="panel-title">
                                            <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                              System Type Information
                                            </a>
                                          </h4>
                                        </div>
                                        <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                                          <div class="panel-body">
                                            <ul>
                                                <li><strong>Organic:</strong> a small team develops a small system with flexible requirements in a highly familiar in-house environment</li>
                                                <li><strong>Embedded:</strong> the system has to operate within very tight constraints and changes to the system very costly.</li>
                                                <li><strong>Semi-detached:</strong> this combines elements of the organic and the embedded types or has characteristics that came between the two.</li>
                                            </ul>
                                          </div>
                                        </div>
                                      </div>
                                    </div>

                                </div>
                                    
                                <div class="col-lg-8 col-md-6 col-sm-12" data-cocomoII>
                                    <h2>@lang('general.cocomoII') <small><span class="cocomo2">0</span> person months</small></h2>

                                    <div class="row">
                                        <div class="col-lg-6 col-md-12">
                                            <table class="table table-striped">
                                                <tr>
                                                    <th>Driver</th>
                                                    <th>Scale</th>
                                                </tr>
                                                @foreach ($sfs as $sf)
                                                <tr>
                                                    <td>
                                                        <a class="btn btn-xs btn-link" data-toggle="popover" title="{{ $sf[count($sf)-2] }}" data-content="{{ $sf[count($sf)-1] }}">
                                                            <span class="glyphicon glyphicon-info-sign"></span>
                                                        </a>
                                                        {{ $sf[0] }}
                                                    </td>
                                                    <td>
                                                        <select class="form-control" data-scale-factor name="{{ $sf[0] }}">
                                                            @for ($i = 1; $i < count($sf)-2; $i++)
                                                            <option  
                                                                @if (!isset($project->cocomoii) && $i == 3)
                                                                selected
                                                                @elseif (isset($project->cocomoii) && $project->cocomoii->$sf[0] == $sf[$i]) 
                                                                selected 
                                                                @endif 
                                                                value="{{ $sf[$i] }}">{{ $ratings[$i] }}</option>
                                                            @endfor
                                                        </select>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>
                                        </div>

                                        <div class="col-lg-6 col-md-12">

                                            <table class="table table-striped">
                                                <tr>
                                                    <th>Effort</th>
                                                    <th>Scale</th>
                                                </tr>
                                                @foreach ($ems as $em)
                                                <tr>
                                                    <td>
                                                        <a class="btn btn-xs btn-link" data-toggle="popover" data-content="{{ $em[count($em)-1] }}">
                                                            <span class="glyphicon glyphicon-info-sign"></span>
                                                        </a>
                                                        {{ $em[0] }}
                                                    </td>
                                                    <td>
                                                        <select class="form-control" data-effort-multiplier name="{{ $em[0] }}" >
                                                            @for ($i = 1; $i < count($em)-1; $i++) 
                                                            @if ($em[$i])
                                                            <option
                                                                @if (!isset($project->cocomoii) && $i == 4)
                                                                selected
                                                                @elseif (isset($project->cocomoii) && $project->cocomoii->$em[0] == $em[$i]) 
                                                                selected 
                                                                @endif 
                                                                value="{{ $em[$i] }}">{{ $ratings[$i-1] }}</option>
                                                            @endif
                                                            @endfor
                                                        </select>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </table>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </form>
        </div>
        <!-- /.panel -->
    </div>
@stop

@section('scripts')

<script>
    (function () {

        var cocomo1 = 0, cocomo2 = 0, kloc = {{ $project->kloc }}, c = 0, k = 0;

        $('body').on('keyup', 'input[name=kloc]', function (e) {
            kloc = parseFloat($(this).val());

            calculateCOCOMO1();
            calculateCOCOMO2();
            updateValues();
        });

        $('body').on('change', 'select[name=system_type]', function (e) {

            c = parseFloat($(this.options[this.selectedIndex]).attr('data-c'));
            k = parseFloat($(this.options[this.selectedIndex]).attr('data-k'));

            calculateCOCOMO1();
            updateValues();
        });

        $('body').on('change', '[data-cocomoII] select', function (e) {
            calculateCOCOMO2();
            updateValues();
        })

        function calculateCOCOMO1() {
            console.log(c, kloc, k, Math.pow(kloc, k));
            cocomo1 = Math.round(c * Math.pow(kloc, k) * 100) / 100;
        }

        function calculateCOCOMO2() {
            var sf = 0;
            var sum_sf = 0;
            var em = 1;

            $("[data-scale-factor]").each(function (index, elem) {
                sum_sf += parseFloat($(elem).val());
            });

            sf = 0.91 + 0.01 * sum_sf;

            $("[data-effort-multiplier]").each(function(index, elem) {
                em *= parseFloat($(elem).val());
            });

            cocomo2 = Math.round(Math.pow(2.94 * kloc, sf) * em * 100) / 100;
        }

        function updateValues() 
        {
            $(".cocomo1").text(cocomo1);
            $(".cocomo2").text(cocomo2);

            console.log(cocomo1, cocomo2);
        }

        var typesBox = $('select[name=system_type]').get(0);
        c = parseFloat($(typesBox.options[typesBox.selectedIndex]).attr('data-c'));
        k = parseFloat($(typesBox.options[typesBox.selectedIndex]).attr('data-k'));

        calculateCOCOMO1();
        calculateCOCOMO2();

        updateValues();

        $(function () {
            $('[data-toggle="popover"]').popover()
        });
    })();
</script>

@stop
