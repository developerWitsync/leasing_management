@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Add New Lease | Residual Value Guarantee - {{ $asset->name }}</div>

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

                        <form role="form" class="form-horizontal">

                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Type of Lease Payments</legend>

                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} required">
                                    <label for="name" class="col-md-4 control-label">Name of Lease Payment</label>
                                    <div class="col-md-6">
                                        <input id="name" type="text" placeholder="Name" class="form-control" name="name" value="{{ old('name') }}">
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }} required">
                                    <label for="type" class="col-md-4 control-label">Type of Lease Payment</label>
                                    <div class="col-md-6">
                                        <select name="type" class="form-control">
                                            <option value="">--Select Lease Payment Type--</option>
                                            @foreach($lease_payments_types as $lease_payments_type)
                                                <option value="{{ $lease_payments_type->id}}">{{ $lease_payments_type->title }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('type'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('type') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('nature') ? ' has-error' : '' }} required">
                                    <label for="type" class="col-md-4 control-label">Nature of Lease Payment</label>
                                    <div class="col-md-6">
                                        <select name="nature" class="form-control">
                                            <option value="">--Select Lease Payment Nature--</option>
                                            @foreach($lease_payments_nature as $nature)
                                                <option value="{{ $nature->id}}">{{ $nature->title }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('nature'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('nature') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('variable_basis') ? ' has-error' : '' }} required variable_basis" style="display: none">
                                    <label for="variable_basis" class="col-md-4 control-label">Variable Basis</label>
                                    <div class="col-md-6">
                                        <input id="variable_basis" type="text" placeholder="Name" class="form-control" name="variable_basis">
                                        @if ($errors->has('variable_basis'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('variable_basis') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('variable_amount_determinable') ? ' has-error' : '' }} required variable_basis" style="display: none">
                                    <label for="variable_amount_determinable" class="col-md-4 control-label">Variable Amount Determinable</label>
                                    <div class="col-md-6">

                                        <div class="col-md-6 form-check form-check-inline">
                                            <input class="form-check-input" name="variable_amount_determinable" type="checkbox" id="yes" value="yes">
                                            <label class="form-check-label" for="yes" style="vertical-align: 4px">Yes</label>
                                        </div>

                                        <div class=" col-md-6 form-check form-check-inline">
                                            <input class="form-check-input" name="variable_amount_determinable" type="checkbox" id="no" value="no">
                                            <label class="form-check-label" for="no" style="vertical-align: 4px">No</label>
                                        </div>
                                    </div>
                                </div>


                                <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                    <label for="description" class="col-md-4 control-label">Any Other Description</label>
                                    <div class="col-md-6">
                                        <input id="description" type="text" placeholder="Description" class="form-control" name="description">
                                        @if ($errors->has('description'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('description') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                            </fieldset>

                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">Lease Payment Periods</legend>
                                <div class="form-group{{ $errors->has('payment_interval') ? ' has-error' : '' }} required">
                                    <label for="payment_interval" class="col-md-4 control-label">Lease Payment Interval</label>
                                    <div class="col-md-6">
                                        <select name="payment_interval" class="form-control">
                                            <option value="">--Select Payment Interval--</option>
                                            @foreach($payments_frequencies as $frequency)
                                                <option value="{{ $frequency->id }}">{{ $frequency->title }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('payment_interval'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('payment_interval') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </fieldset>

                        </form>

                    @endif
                </div>
            </div>



        </div>
    </div>
@endsection
@section('footer-script')
    <script>
        $('select[name="no_of_lease_payments"]').on('change', function(){
            window.location.href = '{{ route("lease.residual.add", ['lease_id' => $lease->id, 'asset_id' => $asset->id]) }}?total_payments='+$(this).val();
        });


        $('select[name="nature"]').on('change', function(){
            if($(this).val() == '2') {
                $('.variable_basis').show();
            } else {
                $('.variable_basis').hide();
            }
        });
    </script>
@endsection