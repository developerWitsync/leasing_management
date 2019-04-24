@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <!-- END CSS for this page -->
    <link href="{{asset('css/nv.d3.css')  }}" rel="stylesheet">
    <script src="{{ asset('js/d3.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/nv.d3.js')}}"></script>
    <script src="{{ asset('js/charts.min.js')}}"></script>
    <style>
        text {
            font: 12px sans-serif;
        }

        svg {
            display: block;
        }

        svg {
            height: 300px !important;
        }

        .nvd3 nv-noData{
            text-anchor: end !important;
        }

        .nvd3.nv-noData {
            position: relative;
            transform: translate(-10%, 0) !important;
            text-align: center;
        }

    </style>
@endsection
@section('content')
    <div class="leasingModuleOuter">
        <div class="leasingMainHd clearfix">{{ $asset->name }} <span> {{ $asset->category->title }} </span>
        </div>
        <div class="assetsNameOuter">
            <div class="assetsTabs">
                <ul>
                    @if(request()->segment(2) == 'valuation-capitalised')
                        <li>
                            <a href="{{ route('leasevaluation.cap.asset', ['id' => $lease->id]) }}">Overivew</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.cap.asset.valuation', ['id' => $lease->id]) }}"  class="active">Valuation</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.cap.interestdepreciation', ['id' => $lease->id]) }}">Interest &amp; Depreciation</a>
                        </li>
                    @else

                        <li>
                            <a href="{{ route('leasevaluation.ncap.asset', ['id' => $lease->id]) }}" class="active">Overivew</a>
                        </li>

                    @endif
                </ul>
            </div>

            @include('leasevaluation.partials._valuation_tab')

            <span id="see_details"></span>

        </div>
    </div>


    <!--Lease Liability Calculus -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content current_modal_body">

            </div>
        </div>
    </div>
    <!--Lease Liability Calculus-->

@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(document).ready(function(){
            var chart;
            //generate chart for the valuations here
            nv.addGraph(function() {
                chart = nv.models.multiBarChart()

                    .reduceXTicks(true)   //If 'false', every single x-axis tick label will be rendered.
                    .rotateLabels(0)      //Angle to rotate x-axis labels.
                    .showControls(true)   //Allow user to switch between 'Grouped' and 'Stacked' mode.
                    .groupSpacing(0.1)    //Distance between each group of bars.
                ;

                chart.xAxis
                    //.tickFormat(d3.format(',f'));
                    .tickFormat(function(d) {
                        // console.log(d.label)
                        // return d3.time.format('%d %b %Y')(new Date(d));
                        return d;
                    });

                // chart.yAxis
                //     .tickFormat(d3.format(',.1f'));

                chart.yAxis
                    .tickFormat(function(d){ return  currencyFormat(parseFloat(d)); });

                d3.select('#chart1 svg')
                    .datum(exampleData())
                    .call(chart);

                nv.utils.windowResize(chart.update);

                return chart;
            });


            function d3Graph(data){
                var final_chart_data = [
                    {
                        "key":"Undiscounted Lease Liability",
                        "values":[],
                        "color" : '#117bb8'
                    },
                    {
                        "key":"Present Value of Lease Liability",
                        "values":[],
                        "color" : '#1b2d5b'
                    },
                    {
                        "key":"Value of Lease Asset",
                        "values":[],
                        "color" : '#75a9d1'
                    }
                ];

                for(i = 0; i < data.length; i++){
                    var effective_date = new Date(data[i].effective_date);
                    var undisounted_lease_liability = {
                        'x' : effective_date.toDateString() + ' (' + data[i].valuation_type + ')',
                        'y' : parseFloat(data[i].undiscounted_value),

                    };

                    var present_value = {
                        'x' : effective_date.toDateString() + ' (' + data[i].valuation_type + ')',
                        'y' : parseFloat(data[i].present_value),

                    };

                    var value_of_lease_asset = {
                        'x' : effective_date.toDateString() + ' (' + data[i].valuation_type +')',
                        'y' : parseFloat(data[i].value_of_lease_asset)
                    };

                    final_chart_data[0].values.push(undisounted_lease_liability);
                    final_chart_data[1].values.push(present_value);
                    final_chart_data[2].values.push(value_of_lease_asset);
                }

                d3.select('#chart1 svg').datum(final_chart_data).transition().duration(500).call(chart);
                nv.utils.windowResize(chart.update);
            }

            //Generate some nice data.
            function exampleData() {
                return [
                    {
                        "key":"Undiscounted Lease Liability",
                        "values":[]
                    },
                    {
                        "key":"Present Value of Lease Liability",
                        "values":[]
                    },
                    {
                        "key":"Value of Lease Asset",
                        "values":[]
                    }
                ];
            }


            $('[data-toggle="tooltip"]').tooltip();
            @if(request()->segment(2) == 'valuation-capitalised')
                var _data_table_url = '{{ route("leasevaluation.cap.asset.fetchvaluations", ["id" => $lease->id]) }}';
            @else
                var _data_table_url = '{{ route("leasevaluation.ncap.asset.fetchvaluations", ["id" => $lease->id]) }}';
            @endif

            function formatNumbers(data){
                return new Intl.NumberFormat('ja-JP', { maximumSignificantDigits: 3 }).format(data);
            }

            var columns = [
                {"data": null, sortable:false},
                {"data": "effective_date", sortable:false},
                {"data": "valuation_type", sortable:false},
                {
                    "data": "daily_discount_rate", sortable:false, render: function (data) {
                        return formatNumbers(data);
                    }
                },
                {
                    "data": "undiscounted_value", sortable:false, render: function (data) {
                        return formatNumbers(data);
                    }
                },
                {"data": "present_value", sortable:false, render: function (data) {
                        return formatNumbers(data);
                    }},
                {"data": "value_of_lease_asset", sortable:false, render: function (data) {
                        return formatNumbers(data);
                    }},
                {"data": "fair_market_value", sortable:false, render : function (data, type, row, meta) {
                        if(data == "null"){
                            return "N/A";
                        } else {
                            return data;
                        }
                    }
                },
                {"data": "impairment_value", sortable:false, render : function (data, type, row, meta){
                        if(data == "null"){
                            return "N/A";
                        } else {
                            return data;
                        }
                    }
                },
                @if($show_statutory_columns)
                    {"data": "exchange_rate", sortable: false},
                    {"data":"statutory_undiscounted_value", sortable:false, render : function(data, type, row, meta){
                        var _return =  parseFloat(row['exchange_rate']) * parseFloat(data);
                        return new Intl.NumberFormat('ja-JP', { maximumSignificantDigits: 3 }).format(_return);
                    }},
                    {"data":"statutory_present_value", sortable:false , render : function(data, type, row, meta){
                            var _return = parseFloat(row['exchange_rate']) * parseFloat(data);
                            return new Intl.NumberFormat('ja-JP', { maximumSignificantDigits: 3 }).format(_return);
                        }},
                    {"data":"statutory_value_of_lease_asset", sortable:false , render : function(data, type, row, meta){
                            var _return = parseFloat(row['exchange_rate']) * parseFloat(data);
                            return new Intl.NumberFormat('ja-JP', { maximumSignificantDigits: 3 }).format(_return);
                        }},
                    {"data":"statutory_fair_market_value", sortable:false , render : function(data, type, row, meta){
                            var _return =  parseFloat(row['exchange_rate']) * parseFloat(data);
                            return new Intl.NumberFormat('ja-JP', { maximumSignificantDigits: 3 }).format(_return);
                        }},
                    {"data":"statutory_impairment_value", sortable:false , render : function(data, type, row, meta){
                            var _return =  parseFloat(row['exchange_rate']) * parseFloat(data);
                            return new Intl.NumberFormat('ja-JP', { maximumSignificantDigits: 3 }).format(_return);
                    }},
                @endif

                {"data": null, sortable : false}
            ];
            var columnDefs = [
                {
                    "targets" : @if($show_statutory_columns) 15 @else 9 @endif,
                    "data" : null,
                    "orderable": false,
                    "className" : "text-center",
                    "render" : function(data, type, full, meta) {
                        var html = "<button  data-toggle='tooltip' data-placement='top' title='See Details' type=\"button\" data-history_id='"+full['history_id']+"' class=\"btn btn-success btn-xs see_details\"><i class=\"fa fa-eye\"></i> </button>";
                        html += "<br/><button  data-toggle='tooltip' data-placement='top' title='PV Calculus' type=\"button\" data-history_id='"+full['history_id']+"' class=\"btn btn-info btn-xs pv_calculus\"><i class=\"fa fa-calculator \"></i> </button>"
                        return html;
                    }
                }
            ];
            //var columnDefs = [];
            $("#complete_lease_valuations").DataTable({
                responsive: true,
                "bPaginate": false,
                "bLengthChange": false,
                "bFilter": false,
                columnDefs : columnDefs,
                "scrollX": true,
                "columns": columns,
                "processing": true,
                "serverSide": true,
                "ajax": {
                    url: _data_table_url,
                    beforeSend: function(){
                      showOverlayForAjax();
                    },
                    dataSrc: function(json) {
                        removeOverlayAjax();
                        d3Graph(json.data);
                        return json.data;
                    }
                },
                "fnRowCallback": function (nRow, aData, iDisplayIndex) {
                    $("td:nth-child(1)", nRow).html(iDisplayIndex + 1);
                    return nRow;
                }
            });

            $(document.body).on('click', '.see_details', function(){
                var history = $(this).data('history_id');
                //call ajax to fetch the view for the Valuation Details.....
                @if(request()->segment(2) == 'valuation-capitalised')
                    var url = '/lease-valuation/valuation-capitalised/see-valuation-details/'+history;
                @else
                    var url = '/lease-valuation/valuation-non-capitalised/see-valuation-details/'+history;
                @endif
                $.ajax({
                    url : url,
                    beforeSend: function () {
                        showOverlayForAjax();
                    },
                    success: function (response) {
                        $('#see_details').html(response);
                        removeOverlayAjax()
                    }
                })
            });

            /**
             * Show the escalation chart from the json data here
             */
            $(document.body).on('click', '.pv_calculus', function(){
                var history = $(this).data('history_id');
                //call ajax to fetch the view for the Valuation Details.....
                @if(request()->segment(2) == 'valuation-capitalised')
                    var url = '/lease-valuation/valuation-capitalised/show-pv-calculus/'+history;
                @else
                    var url = '/lease-valuation/valuation-non-capitalised/show-pv-calculus/'+history;
                @endif
                $.ajax({
                    url : url,
                    beforeSend: function () {
                        showOverlayForAjax();
                    },
                    success: function (response) {
                        removeOverlayAjax();
                        $('.current_modal_body').html(response);
                        $('#myModal').modal('show');
                    }
                })
            });

        });
    </script>
@endsection