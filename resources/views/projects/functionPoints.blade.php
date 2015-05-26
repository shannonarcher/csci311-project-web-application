@extends("dashboard.master")

@section('stylesheets')
    <style>
    #fp_table .form-control {
        width:60px;
    }
    </style>
@stop

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
                        <a href='{{ URL::to("/projects/$project->id/dashboard") }}' class="btn btn-sm btn-default"><i class="fa fa-cube fa-fw"></i> @lang('general.dashboard')</a>
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
                        <i class="fa fa-cubes fa-fw"></i> @lang('general.function_points')
                        <button class="btn btn-xs btn-success pull-right" type="submit"><i class="fa fa-save fa-fw"></i> @lang('general.save')</button>
                    </div>
                    <div class="panel-body">
                        <fieldset>

                            <div class="col-lg-12">
                                <h2>
                                    <span>@lang('general.function_points')</span>
                                    <small><span class="total_fp">0</span></small>
                                </h2>
                            </div>

                            <div class="col-lg-6 col-md-12">

                                <table class="table table-striped" id="fp_table">
                                    <tr><th colspan="5">UFP</th></tr>
                                    <tr><th>Type</th>   <th>Low</th>    <th>Medium</th>     <th>High</th>   <th>Total</th></tr>

                                    <tr>
                                        <td>
                                            <a class="btn btn-xs btn-link" data-toggle="popover" title="Internal Logical File" data-content="Represents user identifiable data that is stored within your application such as tables in a relational database, flat files, etc.">
                                                <span class="glyphicon glyphicon-info-sign"></span>
                                            </a>
                                            ILF
                                        </td>    
                                        <td><input class="form-control" data-ufp-field type="text" id="low_ilf" name="low_ilf" value="{{ $project->function_points->low_ilf or '0' }}" data-complexity="{{ $complexity[0][0] }}" /></td>
                                        <td><input class="form-control" data-ufp-field type="text" id="med_ilf" name="med_ilf" value="{{ $project->function_points->med_ilf or '0' }}" data-complexity="{{ $complexity[0][1] }}" /></td>
                                        <td><input class="form-control" data-ufp-field type="text" id="hi_ilf" name="hi_ilf" value="{{ $project->function_points->hi_ilf or '0' }}" data-complexity="{{ $complexity[0][2] }}" /></td>
                                        <td><input class="form-control" data-ufp-subtotal type="text" id="total_ilf" value="0" name="ilf" disabled /></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <a class="btn btn-xs btn-link" data-toggle="popover" title="External Interface File" data-content="Represents the data that your application will use/reference but is not maintained by your application.">
                                                <span class="glyphicon glyphicon-info-sign"></span>
                                            </a>
                                            EIF
                                        </td>    
                                        <td><input class="form-control" data-ufp-field type="text" id="low_eif" name="low_eif" value="{{ $project->function_points->low_eif or '0' }}" data-complexity="{{ $complexity[1][0] }}" /></td>
                                        <td><input class="form-control" data-ufp-field type="text" id="med_eif" name="med_eif" value="{{ $project->function_points->med_eif or '0' }}" data-complexity="{{ $complexity[1][1] }}" /></td>
                                        <td><input class="form-control" data-ufp-field type="text" id="hi_eif" name="hi_eif" value="{{ $project->function_points->hi_eif or '0' }}" data-complexity="{{ $complexity[1][2] }}" /></td>
                                        <td><input class="form-control" data-ufp-subtotal type="text" id="total_eif" value="0" name="eif" disabled /></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <a class="btn btn-xs btn-link" data-toggle="popover" title="External Inputs" data-content="Input transactions which update ILF's, e.g. Data entry by users, data or file feeds by external applications.">
                                                <span class="glyphicon glyphicon-info-sign"></span>
                                            </a>
                                            EI
                                        </td>    
                                        <td><input class="form-control" data-ufp-field type="text" id="low_ei" name="low_ei" value="{{ $project->function_points->low_ei or '0' }}" data-complexity="{{ $complexity[2][0] }}" /></td>
                                        <td><input class="form-control" data-ufp-field type="text" id="med_ei" name="med_ei" value="{{ $project->function_points->med_ei or '0' }}" data-complexity="{{ $complexity[2][1] }}" /></td>
                                        <td><input class="form-control" data-ufp-field type="text" id="hi_ei" name="hi_ei" value="{{ $project->function_points->hi_ei or '0' }}" data-complexity="{{ $complexity[2][2] }}" /></td>
                                        <td><input class="form-control" data-ufp-subtotal type="text" id="total_ei" value="0" name="ei" disabled /></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <a class="btn btn-xs btn-link" data-toggle="popover" title="External Outputs" data-content="Transactions which extract and display data from ILF's, e.g. reports created by your application where the reports include derived information.">
                                                <span class="glyphicon glyphicon-info-sign"></span>
                                            </a>
                                            EO
                                        </td>    
                                        <td><input class="form-control" data-ufp-field type="text" id="low_eo" name="low_eo" value="{{ $project->function_points->low_eo or '0' }}" data-complexity="{{ $complexity[3][0] }}" /></td>
                                        <td><input class="form-control" data-ufp-field type="text" id="med_eo" name="med_eo" value="{{ $project->function_points->med_eo or '0' }}" data-complexity="{{ $complexity[3][1] }}" /></td>
                                        <td><input class="form-control" data-ufp-field type="text" id="hi_eo" name="hi_eo" value="{{ $project->function_points->hi_eo or '0' }}" data-complexity="{{ $complexity[3][2] }}" /></td>
                                        <td><input class="form-control" data-ufp-subtotal type="text" id="total_eo" value="0" name="eo" disabled /></td>
                                    </tr>

                                    <tr>
                                        <td>
                                            <a class="btn btn-xs btn-link" data-toggle="popover" title="External Inquiry" data-content="User initiated transactions which provide information but do not update ILF's, e.g. reports created by your application but the reports do not contain any derived data.">
                                                <span class="glyphicon glyphicon-info-sign"></span>
                                            </a>
                                            EQ
                                        </td>    
                                        <td><input class="form-control" data-ufp-field type="text" id="low_eq" name="low_eq" value="{{ $project->function_points->low_eq or '0' }}" data-complexity="{{ $complexity[4][0] }}" /></td>
                                        <td><input class="form-control" data-ufp-field type="text" id="med_eq" name="med_eq" value="{{ $project->function_points->med_eq or '0' }}" data-complexity="{{ $complexity[4][1] }}" /></td>
                                        <td><input class="form-control" data-ufp-field type="text" id="hi_eq" name="hi_eq" value="{{ $project->function_points->hi_eq or '0' }}" data-complexity="{{ $complexity[4][2] }}" /></td>
                                        <td><input class="form-control" data-ufp-subtotal type="text" id="total_eq" value="0" name="eq" disabled /></td>
                                    </tr>

                                    <tr>
                                        <td colspan="4">Unadjusted Function Points (UFP)</td>                                        
                                        <td><input class="form-control" type="text" id="total_ufp" value="0" disabled /></td>
                                    </tr>
                                </table>

                                <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingOne">
                                      <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                          ILF / EIF: Assessing Complexity Help
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseOne" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="headingOne">
                                      <div class="panel-body">
                                        For data functions, the rating is based on
                                        <ul>
                                            <li>
                                                RET: The number of <strong>Record Element Types</strong> (i.e. subgroup of data elements) in an ILF or EIF
                                                <ul>
                                                    <li>
                                                     E.g. a customer file that contains Name, Address, etc. In addition all the credit
                                                     cards and credit card numbers of the customer are contained in the file. Hence, 
                                                     there are two RETs in the Customer File.
                                                    </li>
                                                </ul>
                                            </li>
                                            <li>
                                                DET: the number of <strong>Data Element Types</strong> (i.e. <strong>unique, non-repeated</strong> field) in an ILF or EIF.
                                            </li>
                                        </ul>

                                        <table class="table table-striped">
                                            <tr>
                                                <th rowspan="2">RET</th>
                                                <th colspan="3">Data Element Types (DETs)</th>
                                            </tr>
                                            <tr>
                                                <td>1-19</td>
                                                <td>20-50</td>
                                                <td>51+</td>
                                            </tr>
                                            <tr>
                                                <td>1</td>
                                                <td>Low</td>
                                                <td>Low</td>
                                                <td>Medium</td>
                                            </tr>
                                            <tr>
                                                <td>2 to 5</td>
                                                <td>Low</td>
                                                <td>Medium</td>
                                                <td>High</td>
                                            </tr>
                                            <tr>
                                                <td>6 or more</td>
                                                <td>Medium</td>
                                                <td>High</td>
                                                <td>High</td>
                                            </tr>
                                        </table>
                                      </div>
                                    </div>
                                  </div>

                                  <div class="panel panel-default">
                                    <div class="panel-heading" role="tab" id="headingTwo">
                                      <h4 class="panel-title">
                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                          EI / EO / EQ: Assessing Complexity Help
                                        </a>
                                      </h4>
                                    </div>
                                    <div id="collapseTwo" class="panel-collapse collapse out" role="tabpanel" aria-labelledby="headingTwo">
                                      <div class="panel-body">
                                        For transactional functions, the rating is based on
                                        <ul>
                                            <li>
                                                FTR: the number of File Type References (ILFs or EIFs) in a transaction
                                            </li>
                                            <li>
                                                DET: the number of Data Element Types in a transaction
                                            </li>
                                        </ul>

                                        <h4>For EO and EQ</h4>

                                        <table class="table">
                                            <tr>
                                                <th rowspan="2">FTRs</th>
                                                <th colspan="3">Data Element Types (DETs)</th>
                                            </tr>
                                            <tr>
                                                <td>1-5</td>
                                                <td>6-19</td>
                                                <td>20+</td>
                                            </tr>
                                            <tr>
                                                <td>0-1</td>
                                                <td>Low</td>
                                                <td>Low</td>
                                                <td>Medium</td>
                                            </tr>
                                            <tr>
                                                <td>2 to 3</td>
                                                <td>Low</td>
                                                <td>Medium</td>
                                                <td>High</td>
                                            </tr>
                                            <tr>
                                                <td>4 or more</td>
                                                <td>Medium</td>
                                                <td>High</td>
                                                <td>High</td>
                                            </tr>
                                        </table>

                                        <h4>For EI</h4>

                                        <table class="table">
                                            <tr>
                                                <th rowspan="2">FTRs</th>
                                                <th colspan="3">Data Element Types (DETs)</th>
                                            </tr>
                                            <tr>
                                                <td>1-4</td>
                                                <td>5-15</td>
                                                <td>16+</td>
                                            </tr>
                                            <tr>
                                                <td>0-1</td>
                                                <td>Low</td>
                                                <td>Low</td>
                                                <td>Medium</td>
                                            </tr>
                                            <tr>
                                                <td>2</td>
                                                <td>Low</td>
                                                <td>Medium</td>
                                                <td>High</td>
                                            </tr>
                                            <tr>
                                                <td>3 or more</td>
                                                <td>Medium</td>
                                                <td>High</td>
                                                <td>High</td>
                                            </tr>
                                        </table>
                                      </div>
                                    </div>
                                  </div>
                                </div>
                            </div>

                            <div class="col-lg-6 col-md-12">
                                <table class="table table-striped">
                                    <tr><th colspan="2">VAF</th></tr>
                                    <tr>
                                        <th>General System Characteristics (GSCs)</th>
                                        <th>
                                            <a class="btn btn-xs btn-link" data-toggle="popover" title="External Inquiry" data-content="0:No Influence, 1:Incidental, 2:Moderate, 3:Average, 4:Significant, 5:Essential">
                                                <span class="glyphicon glyphicon-info-sign"></span>
                                            </a>
                                            Degree of Influence
                                        </th>
                                    </tr>

                                    @for ($i = 0; $i < count($gscs); $i++)
                                    <?php $gsc_name = "gsc_".($i+1); ?>
                                    <tr>    
                                        <td>
                                            <a class="btn btn-xs btn-link" data-toggle="popover" data-content="{{ $gscs[$i][1] }}">
                                                <span class="glyphicon glyphicon-info-sign"></span>
                                            </a>
                                            {{ $gscs[$i][0] }}
                                        </td>
                                        <td>
                                        @for ($j = 0; $j <= 5; $j++)
                                        <input data-vaf-field type="radio" 
                                            @if (isset($project->function_points) && $project->function_points->$gsc_name == $j) 
                                            checked 
                                            @elseif ($j == 0)
                                            checked 
                                            @endif 
                                            name="gsc_{{ $i+1 }}" value="{{ $j }}" /> {{ $j }}
                                        @endfor
                                        </td>
                                    </tr>
                                    @endfor

                                    <tr>
                                        <td>Total Degree of Influence (TDI)</td>
                                        <td><input class="form-control" type="text" id="total_di" value="0" disabled /></td>
                                    </tr>

                                    <tr>
                                        <td>Value Adjustment Factor (VAF)</td>
                                        <td><input class="form-control" type="text" id="total_vaf" value="0" disabled /></td>
                                    </tr>
                                </table>
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
    var ufp = 0, di = 0, vaf = 0;

    $("body").on('keyup', '[data-ufp-field]', function (e) {
        $(this).val($(this).val().replace(/\D/g,''));
        updateUFP();
        updateFP();
    });

    $("body").on('change', '[data-vaf-field]', function (e) {
        updateVAF();
        updateFP();
    });

    function updateUFP() {
        var total = 0;
        $('#fp_table tr').each(function (index, elem) {
            var subtotal = 0;
            $(elem).find('input[data-ufp-field]').each(function (fi, f) {
                subtotal += ($(f).val() != '' ? $(f).val() : 0) * $(f).attr('data-complexity');
            });

            $(elem).find('input[data-ufp-subtotal]').val(subtotal);
            total += subtotal;
        });

        ufp = total;
    }

    function updateVAF() {
        var total = 0;
        for (var i = 1; i <= 14; i++) {
            total += parseInt($("input[name=gsc_" + i + "]:checked").val());
        };
        di = total;
        $("#total_di").val(di);

        vaf = total * 0.01 + 0.65;
    }

    function updateFP() {
        $('#total_ufp').val(ufp);
        $('#total_vaf').val(vaf);
        $('.total_fp').text(Math.round(ufp * vaf * 100) / 100);
    }

    updateUFP();
    updateVAF();
    updateFP();

    $(function () {
        $('[data-toggle="popover"]').popover()
    });
</script>

@stop
