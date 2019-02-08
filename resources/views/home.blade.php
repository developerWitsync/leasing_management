@extends('layouts.app')

@section('header-styles')

    <link href="{{asset('css/nv.d3.css')  }}" rel="stylesheet">
    <script src="{{ asset('js/d3.min.js') }}" charset="utf-8"></script>
    <script src="{{ asset('js/nv.d3.js')}}"></script>


@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading clearfix dashHd">Dashboard
            <span class="rgt badge badge-info">{{ \Carbon\Carbon::today()->format(config('settings.date_format')) }}</span>
        </div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="content-wrapper">
                <div class="">
                    <div class="row">
                        <!-- Icon Cards-->
                        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                            <div class="inforide">
                                <div class="dashboxHd blueBg">Total Active Lease Assets</div>
                                <div class="dashCounting">{{$total_active_lease_asset}}</div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                            <div class="inforide">
                                <div class="dashboxHd  redBg" >Capitalized Assets</div>
                                <div class="dashCounting">
                                    <span><small>Total Own Lease -</small> {{ $own_assets_capitalized }}</span>
                                    <span><small>Total Sub Lease -</small> {{ $sublease_assets_capitalized }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                            <div class="inforide">
                                <div class="dashboxHd greenBg">Land</div>
                                <div class="dashCounting">{{$total_land}}</div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
            <div class="content-wrapper">
                <div class="">
                    <div class="row">

                        <!-- Icon Cards-->
                        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                            <div class="inforide">
                                <div class="dashboxHd blueBg">Other Than Land</div>
                                <div class="dashCounting">{{$total_other_land}}</div>
                            </div>
                        </div>

                        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                            <div class="inforide">
                                <div class="dashboxHd redBg">Plants & Equipments</div>
                                <div class="dashCounting">{{ $total_plant }}</div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                            <div class="inforide">
                                <div class="dashboxHd greenBg">Investment Properties</div>
                                <div class="dashCounting">{{$total_investment}}</div>
                                   
                            </div>
                        </div>
                         <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                            <div class="inforide">
                                <div class="dashboxHd blueBg">Intangible Assets</div>
                                <div class="dashCounting">{{$total_intangible}}</div>
                             </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                            <div class="inforide">
                                <div class="dashboxHd redBg">Agricultural Assets</div>
                                <div class="dashCounting">{{$total_agricultural}}</div>
                             </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Graph Chart -->
            <div class="content-wrapper">
                <div class="">
                    <div class="">
                        <div class="col-md-12 chartBg" id="chart1">
                            <svg></svg>
                        </div>
                        <div class="col-md-12 chartBg" id="chart2">
                            <svg></svg>
                        </div>
                    </div>
                </div>
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                            <div class="inforide">
                                <div class="dashboxHd blueBg">Short Term Lease</div>
                                <div class="dashCounting">{{$total_short_term_lease}}</div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                            <div class="inforide">
                                <div class="dashboxHd redBg">Low Value Lease Assets </div>
                                <div class="dashCounting">{{$total_low_value_asset}}</div>
                            </div>
                        </div>
                        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
                            <div class="inforide">
                                <div class="dashboxHd greenBg">Other Lease Asset</div>
                                <div class="dashCounting">{{$total_other_lease_asset}}</div>  
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection

@section('footer-script')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function(){
            var historicalBarChart1 = [];
            var historicalBarChart2 = [];
            generateFirstGraph();
            generateSecondGraph();
            $.ajax({
                url : "{{ route('home.fetchdetails') }}",
                type : 'get',
                dataType : 'json',
                success: function(data) {
                    if(data.status){
                        historicalBarChart1 = [
                            {
                                key: "Cumulative Return1",
                                values: [
                                    {
                                        "label" : "Undiscounted value",
                                        "value" : [data.total_undiscounted_value]
                                    } ,
                                    {
                                        "label" : "Present value" ,
                                        "value" : [data.total_present_value_lease_asset]
                                    } ,
                                ]
                            }
                        ];
                        historicalBarChart2 = [
                            {
                                key: "Cumulative Return",
                                values: [
                                    {
                                        "label" : "Tangible properties" ,
                                        "value" : [data.total_tengible_undiscounted_value]
                                    } ,
                                    {
                                        "label" : "Tangible Present value" ,
                                        "value" : [data.total_tengible_present_value_lease_asset]
                                    } ,
                                    {
                                        "label" : "Tangible Other land" ,
                                        "value" : [data.total_tengible_other_undiscounted_value]
                                    } ,
                                    {
                                        "label" : "Tangible other than land Present value" ,
                                        "value" : [data.total_tengible_other_present_value_lease_asset]
                                    } ,
                                    {
                                        "label" : "Plant & Machineries Undiscounted" ,
                                        "value" : [data.total_plants_undiscounted]
                                    } ,
                                    {
                                        "label" : "Plant & Machineries Present value" ,
                                        "value" : [data.total_plants_present_value]
                                    } ,
                                    {
                                        "label" : "Investment & Properties Undiscounted" ,
                                        "value" : [data.total_invest_undiscounted]
                                    } ,
                                    {
                                        "label" : "Investment Present value" ,
                                        "value" : [data.total_invest_present_value]
                                    } ,
                                ]
                            }
                        ];
                        generateFirstGraph();
                        generateSecondGraph();
                    }
                }
            });

            function generateFirstGraph(){
                nv.addGraph(function() {
                    var chart = nv.models.discreteBarChart()
                        .x(function(d) { return d.label })
                        .y(function(d) { return d.value })
                        .staggerLabels(true)
                        //.staggerLabels(historicalBarChart[0].values.length > 8)
                        .showValues(true)
                        .duration(250)
                    ;

                    d3.select('#chart1 svg')
                        .datum(historicalBarChart1)
                        .call(chart);

                    nv.utils.windowResize(chart.update);
                    return chart;
                });
            }
            function generateSecondGraph(){
                nv.addGraph(function() {
                    var chart = nv.models.discreteBarChart()
                        .x(function(d) { return d.label })
                        .y(function(d) { return d.value })
                        .staggerLabels(true)
                        //.staggerLabels(historicalBarChart[0].values.length > 8)
                        .showValues(true)
                        .duration(250)
                    ;

                    d3.select('#chart2 svg')
                        .datum(historicalBarChart2)
                        .call(chart);

                    nv.utils.windowResize(chart.update);
                    return chart;
                });
            }
        });



    </script>
@endsection
