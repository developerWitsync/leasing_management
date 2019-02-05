@extends('layouts.app')

<link href="{{  asset('css/nv.d3.css') }} rel="stylesheet" type="text/css">
<script src="{{ asset('js/d3.min.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/nv.d3.js')}}"></script>

    <style>
        text {
            font: 12px sans-serif;
        }
        svg {
            display: block;
        }
         #chart1, svg {
            margin: 0px;
            padding: 0px;
            height: 100%;
            width: 50%;
        }
        #chart2, svg {
            margin: 0px;
            padding: 0px;
            height: 100%;
            width: 100%;
        }
    </style>

@section('content')
        <div class="panel panel-default">
            <div class="panel-heading">Dashboard</div>

            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

    <div class="content-wrapper">
    <div class="container-fluid">
    <div class="row">

        <!-- Icon Cards-->
        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
               <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                    <h4>Total Number Of Active Lease Assets</h4>
                    <h5>{{$total_active_lease_asset}}</h5>
                </div>
              </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                    <h4>Capitalized Assets</h4>
                    <h5>Total Own Lease-  {{ $own_assets_capitalized }}
                       Total Sub Lease-  {{ $sublease_assets_capitalized }}</h5>
                </div>
              </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
                
                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                    <h4>Land</h4>
                    <h5>{{$total_land}}</h5>
                </div>
              </div>
            </div>
        </div>
     </div>
  </div>
</div>
<div class="content-wrapper">
    <div class="container-fluid">
    <div class="row">

        <!-- Icon Cards-->
        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
               <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                    <h4>Other Than Land</h4>
                    <h5>{{$total_other_land}}</h5>
                </div>
              </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                    <h4>Plants & Equipments</h4>
                    <h5>{{ $total_plant }}</h5>
              </div>
            </div>
        </div>
    </div>
    <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
                
                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                    <h4>Investment Properties</h4>
                    <h5>{{$total_investment}}</h5>
                </div>
              </div>
            </div>
        </div>
     </div>
  </div>
</div>
<!-- Graph Chart -->
<div class="row">
    <div class="col-md-4" id="chart1"> 
        <svg></svg>
    </div>
    <div class="col-md-8" id="chart2">
        <svg></svg>
    </div>
</div>
<div class="content-wrapper">
    <div class="container-fluid">
    <div class="row">
<div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
                
                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                    <h4>Short Term Lease</h4>
                    <h5>{{$total_short_term_lease}}</h5>
                </div>
              </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
                
                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                    <h4>Low Value Lease Assets </h4>
                    <h5>{{$total_low_value_asset}}</h5>
                </div>
              </div>
            </div>
        </div>
         <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
                
                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                    <h4>Other Lease Asset</h4>
                    <h5>{{$total_other_lease_asset}}</h5>
                </div>
              </div>
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
