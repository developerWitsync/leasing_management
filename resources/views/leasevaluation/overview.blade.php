@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <!-- END CSS for this page -->
    {{--<link href="{{asset('css/nv.d3.css')  }}" rel="stylesheet">--}}
    {{--<script src="{{ asset('js/d3.min.js') }}" charset="utf-8"></script>--}}
    {{--<script src="{{ asset('js/nv.d3.js')}}"></script>--}}
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
                            <a href="{{ route('leasevaluation.cap.asset', ['id' => $lease->id]) }}" class="active">Overview</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.cap.asset.valuation', ['id' => $lease->id]) }}">Valuation</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.cap.interestdepreciation', ['id' => $lease->id]) }}">Interest &amp; Depreciation</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.cap.expensereport', ['id' => $lease->id]) }}">Lease Expense</a>
                        </li>
                    @else

                        <li>
                            <a href="{{ route('leasevaluation.ncap.asset', ['id' => $lease->id]) }}" class="active">Overivew</a>
                        </li>

                        <li>
                            <a href="{{ route('leasevaluation.ncap.expensereport', ['id' => $lease->id]) }}">Lease Expense</a>
                        </li>

                    @endif

                </ul>
            </div>

            @include('leasevaluation.partials._asset_overview_tab')
        </div>
    </div>

    <!--Escalations Chart -->
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="dialog">
            <div class="modal-content escalation_chart_modal_body">

            </div>
        </div>
    </div>

    <!--Escalations Chart -->

@endsection
@section('footer-script')
    <script>
        $(document).ready(function(){
            $('[data-toggle="tooltip"]').tooltip();
        });
        @if(request()->segment(2) == 'valuation-capitalised')
            var discount_rate_chart_url = '{{ route("leasevaluation.cap.discountRateChart", ["id" => $lease->id]) }}';
        @else
            var discount_rate_chart_url = '{{ route("leasevaluation.ncap.discountRateChart", ["id" => $lease->id]) }}';
        @endif
    </script>
    <script>

        $.ajax({
            url : discount_rate_chart_url,
            success : function(response){
                var values = response;

                var myLineChart = new Chart( document.getElementById('canvas').getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: response.x,
                        datasets: [{
                            label: 'Discount Rates',
                            backgroundColor: 'rgb(255, 99, 132)',
                            borderColor: 'rgb(255, 99, 132)',
                            data: response.y,
                            fill: false,
                        }]
                    },
                    options: {
                        responsive: true,
                        title: {
                            display: true,
                            text: 'Discount Rates over the effective dates'
                        },
                        tooltips: {
                            mode: 'index',
                            intersect: false,
                        },
                        hover: {
                            mode: 'nearest',
                            intersect: true
                        },
                        scales: {
                            xAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Effective Date'
                                }
                            }],
                            yAxes: [{
                                display: true,
                                scaleLabel: {
                                    display: true,
                                    labelString: 'Discount Rate (%)'
                                }
                            }]
                        }
                    }
                });
            }
        });

        $(function () {
            $('.show_escalation').on('click', function(){
                var payment_id = $(this).data('payment_id');
                $.ajax({
                    url : '/lease-valuation/show-escalation-chart/'+payment_id,
                    beforeSend : function(){
                      showOverlayForAjax();
                    },
                    success:function(response){
                        $(".escalation_chart_modal_body").html(response);
                        $("#myModal").modal("show");
                        removeOverlayAjax();
                    }
                })
            });
        })

    </script>
@endsection