@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Add New Lease | Add Lease Payments for asset - {{ $asset->name }}</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                    <div>
                        Asset Name : <span class="badge badge-success">{{ $asset->name }}</span>
                    </div>
                    <div>
                        Unique ULA Code : <span class="badge badge-primary">{{ $asset->uuid }}</span>
                    </div>
                    <div>
                        Lease Start Date(Including Free Period) : <span class="badge badge-warning">{{ date('F,d Y', strtotime($asset->accural_period)) }}</span>
                    </div>

                    <div>
                        Lease End Date : <span class="badge badge-warning">{{ date('F,d Y', strtotime($asset->lease_end_date)) }}</span>
                    </div>

                    <div class="row form-group" style="margin-top: 12px;">
                        <div class="col-md-4">
                            <label for="no_of_lease_payments">Number of Lease Payments</label>
                        </div>
                        <div class="col-md-8">
                            <select name="no_of_lease_payments" class="form-control">
                                <option value="">--Select Number of Lease Payments</option>
                                @foreach($lease_asset_number_of_payments as $number_of_payment)
                                    <option value="{{ $number_of_payment['id'] }}" @if($total_payments == $number_of_payment['id']) selected="selected" @endif>{{ $number_of_payment['number'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($total_payments > 0)
                        <div class="stepwizard">
                            <div class="stepwizard-row setup-panel">
                                @for($i = 1;$i <= $total_payments; $i++)
                                    <div class="stepwizard-step">
                                        <a href="#step-1" type="button" class="btn @if($i == 1) btn-primary @else btn-default @endif btn-circle">{{ $i }}</a>
                                        <p>Create Lease Payment Number {{ $i }}</p>
                                    </div>
                                @endfor
                            </div>
                        </div>

                        @include('lease.payments._form')

                    @endif
                </div>
            </div>



        </div>
    </div>
@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script>

        $("#first_payment_start_date").datepicker({
            dateFormat: "dd-M-yy",
        });

        $("#last_payment_end_date").datepicker({
            dateFormat: "dd-M-yy",
        });

        $('select[name="no_of_lease_payments"]').on('change', function(){
            window.location.href = '{{ route("lease.payments.add", ['lease_id' => $lease->id, 'asset_id' => $asset->id]) }}?total_payments='+$(this).val();
        });


        //If Variable Basis selected
        $('select[name="nature"]').on('change', function(){
            if($(this).val() == '2') {
                $('.variable_basis').show();
            } else {
                $('.variable_basis').hide();

                //change the values to null as well
                $('#variable_basis').val('');
                $('input[name="variable_amount_determinable"]').prop("checked",false);
            }
        });

        //function to calculate the last lease payment end date
        function calculateLastPaymentEndDate(that, firstPaymentStartDate){
            var _calculated_last_payment_date = new Date();
            var _selected_payment_interval = parseInt($(that).val());
            var _payout_value = parseInt($('select[name="payout_time"]').val());
            if(_payout_value == 2) {
                @php
                    $calculated_date = \Carbon\Carbon::parse($asset->lease_end_date);
                @endphp
                    _calculated_last_payment_date = new Date("{{ $calculated_date }}");
            } else {
                switch (_selected_payment_interval) {
                    case 1:
                        _calculated_last_payment_date = firstPaymentStartDate;
                        break;
                    case 2:
                        //means selected option is monthly
                        @php
                            $lease_end_date = \Carbon\Carbon::parse($asset->lease_end_date);
                            $calculated_date = $lease_end_date->subMonth(1)->format('D M d Y');
                        @endphp
                            _calculated_last_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    case 3:
                        //means selected option is Quarterly
                        @php
                            $lease_end_date = \Carbon\Carbon::parse($asset->lease_end_date);
                            $calculated_date = $lease_end_date->subMonth(3)->format('D M d Y');
                        @endphp
                            _calculated_last_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    case 4:
                        //means selected option is Semi-Annually
                        @php
                            $lease_end_date = \Carbon\Carbon::parse($asset->lease_end_date);
                            $calculated_date = $lease_end_date->subMonth(6)->format('D M d Y');
                        @endphp
                            _calculated_last_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    case 5:
                        //means selected option is Annually
                        @php
                            $lease_end_date = \Carbon\Carbon::parse($asset->lease_end_date);
                            $calculated_date = $lease_end_date->subMonth(12)->format('D M d Y');
                        @endphp
                            _calculated_last_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    default:
                        break;
                }
            }
            $("#last_payment_end_date").datepicker("setDate", new Date(_calculated_last_payment_date));
        }

        //calculate the First Lease Payment Start Date and Last Lease Payment End Date here
        $('select[name="payout_time"] , select[name="payment_interval"]').on('change', function(){

            var _value = parseInt($('select[name="payout_time"]').val());
            var _selected_payment_interval = parseInt($('select[name="payment_interval"]').val());
            var _start_date = new Date("{{ date('D M d Y', strtotime($asset->accural_period)) }}");
            var _end_date   = new Date("{{ date('D M d Y', strtotime($asset->lease_end_date)) }}");

            if(_value == "" || _selected_payment_interval == "") {
                return false;
            }

            var _calculated_first_payment_date = new Date();
            if(_value == 1) {
                //means At Lease Interval Start
                _calculated_first_payment_date = _start_date;
            } else {
                //means At Lease Interval End
                switch (_selected_payment_interval) {
                    case 1:
                        _calculated_first_payment_date = _end_date;
                        break;
                    case 2:
                        //means selected option is monthly
                        @php
                            $accural_date = \Carbon\Carbon::parse($asset->accural_period);
                            $calculated_date = $accural_date->addMonth(1)->format('D M d Y');
                        @endphp
                        _calculated_first_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    case 3:
                        //means selected option is Quarterly
                        @php
                            $accural_date = \Carbon\Carbon::parse($asset->accural_period);
                            $calculated_date = $accural_date->addMonth(3)->format('D M d Y');
                        @endphp
                        _calculated_first_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    case 4:
                        //means selected option is Semi-Annually
                        @php
                            $accural_date = \Carbon\Carbon::parse($asset->accural_period);
                            $calculated_date = $accural_date->addMonth(6)->format('D M d Y');
                        @endphp
                            _calculated_first_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    case 5:
                        //means selected option is Annually
                        @php
                            $accural_date = \Carbon\Carbon::parse($asset->accural_period);
                            $calculated_date = $accural_date->addMonth(12)->format('D M d Y');
                        @endphp
                            _calculated_first_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    default:
                        break;
                }
            }
            //populate the value to the First Payment Date datepicker
            $("#first_payment_start_date").datepicker("setDate", new Date(_calculated_first_payment_date));
            //calculate the Lease payment End Date here
            calculateLastPaymentEndDate($('select[name="payment_interval"]'), new Date(_calculated_first_payment_date));

        });

        $(document).ready(function () {
            $("input[type='checkbox']").on('click', function(){
                var group = "input[name='"+$(this).attr("name")+"']";
                $(group).prop("checked",false);
                $(this).prop("checked",true);
            });

            var _start_date = new Date("{{ date('D M d Y', strtotime($asset->accural_period)) }}");
            if(_start_date < new Date('January 01 2019')){
                $('.using_lease_payment').show();
            } else {
                $('.using_lease_payment').hide();
            }

            $('input[name="using_lease_payment"]').on('click', function(){
                if($(this).is(":checked") && $(this).val() == '1'){
                    var message = "You are required to place escalation rates if applicable, effective from 2019.";
                } else {
                    var message = "You are required to place escalation rates if applicable, effective from the Lease Start Date.";
                }

                bootbox.alert(message);
            });
        });
    </script>
@endsection