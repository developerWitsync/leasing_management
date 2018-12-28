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
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script>

        $("#start_date").datepicker({
            dateFormat: "dd-M-yy",
        });

        $('select[name="no_of_lease_payments"]').on('change', function(){
            window.location.href = '{{ route("lease.payments.add", ['lease_id' => $lease->id, 'asset_id' => $asset->id]) }}?total_payments='+$(this).val();
        });


        $('select[name="nature"]').on('change', function(){
            if($(this).val() == '2') {
                $('.variable_basis').show();
            } else {
                $('.variable_basis').hide();
            }
        });

        //calculate the First Lease Payment Start Date here
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
            $("#start_date").datepicker("setDate", new Date(_calculated_first_payment_date));

        });
    </script>
@endsection