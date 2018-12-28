@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Add New Lease | Underlying Lease Asset Complete Details</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                    <form class="form-horizontal" method="POST">
                        {{ csrf_field() }}

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Basic Details of the Underlying Lease Asset</legend>
                            <div class="form-group{{ $errors->has('other_details') ? ' has-error' : '' }} required">
                                <label for="other_details" class="col-md-4 control-label">Any other Details of the Underlying Lease Asset</label>
                                <div class="col-md-6">
                                    <input id="other_details" type="text" placeholder="Other Details" class="form-control" name="other_details" value="{{ old('other_details', $asset->other_details) }}" >
                                    @if ($errors->has('other_details'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('other_details') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Location of the Underlying Lease Asset</legend>
                            <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }} required">
                                <label for="country" class="col-md-4 control-label">Country</label>
                                <div class="col-md-6">
                                    <select name="country_id" class="form-control">
                                        <option value="">--Select Country--</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->id }}" @if(old('country_id', $asset->country_id) == $country->id) selected="selected" @endif>{{ $country->name }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('country_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('country_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }} required">
                                <label for="location" class="col-md-4 control-label">Place Where Located</label>
                                <div class="col-md-6">
                                    <input id="location" type="text" placeholder="Place Where Located" class="form-control" name="location" value="{{ old('location', $asset->location) }}" >
                                    @if ($errors->has('location'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('location') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Purpose of the Underlying Lease Asset</legend>
                            <div class="form-group{{ $errors->has('specific_use') ? ' has-error' : '' }} required">
                                <label for="specific_use" class="col-md-4 control-label">Specific Use of the Lease Asset</label>
                                <div class="col-md-6">
                                    <select name="specific_use" class="form-control">
                                        <option value="">--Select Use Of Lease Asset--</option>
                                        @foreach($use_of_lease_asset as $use)
                                            <option value="{{ $use->id }}" @if(old('specific_use', $asset->specific_use) == $use->id) selected="selected" @endif>{{ $use->title }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('specific_use'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('specific_use') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('use_of_asset') ? ' has-error' : '' }} required use_of_asset" @if(old('specific_use', $asset->specific_use) == '1') style="display: block" @else style="display: none" @endif>
                                <label for="use_of_asset" class="col-md-4 control-label">State Use Of Asset</label>
                                <div class="col-md-6">
                                    <input id="use_of_asset" type="text" placeholder="State Use Of Asset" class="form-control" name="use_of_asset" value="{{ old('use_of_asset', $asset->use_of_asset) }}" >
                                    @if ($errors->has('use_of_asset'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('use_of_asset') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Expected Useful Life of the Underlying Lease Asset</legend>
                            <div class="form-group{{ $errors->has('expected_life') ? ' has-error' : '' }} required">
                                <label for="expected_life" class="col-md-4 control-label">Specific Use of the Lease Asset</label>
                                <div class="col-md-6">
                                    <select name="expected_life" class="form-control">
                                        <option value="">--Expected Life Of Lease Asset--</option>
                                        @foreach($expected_life_of_assets as $life)
                                            <option value="{{ $life->id }}" @if(old('expected_life', $asset->expected_life) == $life->id) selected="selected" @endif>{{ ($life->years > 0)?$life->years:'Indefinite' }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('expected_life'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('expected_life') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </fieldset>


                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Lease Term of the Underlying Lease Asset</legend>
                            <div class="form-group{{ $errors->has('lease_start_date') ? ' has-error' : '' }} required">
                                <label for="lease_start_date" class="col-md-4 control-label">Lease Start Date</label>
                                <div class="col-md-6">
                                    <input id="lease_start_date" type="text" placeholder="Lease Start Date" class="form-control" name="lease_start_date" value="{{ old('lease_start_date', $asset->lease_start_date) }}" >
                                    @if ($errors->has('lease_start_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_start_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('lease_free_period') ? ' has-error' : '' }} required">
                                <label for="lease_free_period" class="col-md-4 control-label">Initial Lease Free Period, If any(In Days)</label>
                                <div class="col-md-6">
                                    <input id="lease_free_period" type="text" placeholder="Number of Days" class="form-control" name="lease_free_period" value="{{ old('lease_free_period', $asset->lease_free_period) }}" >
                                    @if ($errors->has('lease_free_period'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_free_period') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('accural_period') ? ' has-error' : '' }} required">
                                <label for="accural_period" class="col-md-4 control-label">Start Date of Lease Payment / Accrual Period</label>
                                <div class="col-md-6">
                                    <input id="accural_period" type="text" placeholder="Start Date of Lease Payment / Accrual Period" class="form-control" name="accural_period" value="{{ old('accural_period', $asset->accural_period) }}" readonly="readonly" style="pointer-events: none">
                                    @if ($errors->has('accural_period'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('accural_period') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('lease_end_date') ? ' has-error' : '' }} required">
                                <label for="lease_end_date" class="col-md-4 control-label">Lease End Date, Non-Cancellable Period</label>
                                <div class="col-md-6">
                                    <input id="lease_end_date" type="text" placeholder="Lease End Date, Non-Cancellable Period" class="form-control" name="lease_end_date" value="{{ old('lease_end_date', $asset->lease_end_date) }}" >
                                    @if ($errors->has('lease_end_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_end_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('lease_term') ? ' has-error' : '' }} required">
                                <label for="lease_term" class="col-md-4 control-label">Lease Term (in Months & Years)</label>
                                <div class="col-md-6">
                                    <input id="lease_term" type="text" placeholder="Lease Term (in Months & Years)" class="form-control" name="lease_term" value="{{ old('lease_term', $asset->lease_term) }}" readonly="readonly">
                                    @if ($errors->has('lease_term'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_term') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </fieldset>

                        <fieldset class="scheduler-border">
                            <legend class="scheduler-border">Lease Asset Accounting Adopted Prior to 2019</legend>
                            <div class="form-group{{ $errors->has('accounting_treatment') ? ' has-error' : '' }} required">
                                <label for="accounting_treatment" class="col-md-4 control-label">Lease Asset Accounting Treatment Followed Upto 2018</label>
                                <div class="col-md-6">
                                    <select name="accounting_treatment" class="form-control">
                                        <option value="">--Lease Accounting Treatment--</option>
                                            @foreach($accounting_terms as $accounting_term)
                                                <option value="{{ $accounting_term['id']}}" @if(old('accounting_treatment', $asset->accounting_treatment) == $accounting_term['id']) selected="selected" @endif>{{ $accounting_term['title'] }}</option>
                                            @endforeach
                                    </select>

                                    @if ($errors->has('accounting_treatment'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('accounting_treatment') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </fieldset>


                        <div class="form-group">
                            <div class="col-md-6 col-md-offset-4">
                                <button type="submit" class="btn btn-success">
                                    Submit
                                </button>
                                <a href="{{ route('addlease.leaseasset.index', ['id' => $lease->id,'total_assets' => count($lease->assets)]) }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer-script')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script>
        $(document).ready(function () {
            $('select[name="specific_use"]').on('change', function () {
                if($(this).val() == '1') {
                    $('.use_of_asset').show();
                } else {
                    $('.use_of_asset').hide();
                }
            });

            $(function() {

                $("#lease_start_date").datepicker({
                    dateFormat: "dd-M-yy",
                    maxDate: 0,
                    onSelect: function () {
                        var dt2 = $('#lease_end_date');
                        var startDate = $(this).datepicker('getDate');
                        //add 30 days to selected date
                        startDate.setDate(startDate.getDate() + 30);
                        var minDate = $(this).datepicker('getDate');
                        var dt2Date = dt2.datepicker('getDate');
                        //difference in days. 86400 seconds in day, 1000 ms in second
                        var dateDiff = (dt2Date - minDate)/(86400 * 1000);

                        // //dt2 not set or dt1 date is greater than dt2 date
                        // if (dt2Date == null || dateDiff < 0) {
                        //     dt2.datepicker('setDate', minDate);
                        // }
                        // //dt1 date is 30 days under dt2 date
                        // else if (dateDiff > 30){
                        //     dt2.datepicker('setDate', startDate);
                        // }
                        //sets dt2 maxDate to the last day of 30 days window
                        // dt2.datepicker('option', 'maxDate', startDate);
                        //first day which can be selected in dt2 is selected date in dt1
                        dt2.datepicker('option', 'minDate', minDate);

                        setTimeout(function(){
                            calculateLeaseTerm();
                        }, 100);
                    }
                });

                $('#lease_end_date').datepicker({
                    dateFormat: "dd-M-yy",
                    minDate: 0,
                    onSelect : function (){
                        calculateLeaseTerm();
                    }
                });

                function calculateLeaseTerm(){
                    var date_diff = dateDiff($('#accural_period').datepicker('getDate'), $('#lease_end_date').datepicker('getDate'));
                    var difference_string = date_diff.years + " years "+ date_diff.months+ " months "+ date_diff.days+" days";
                    $("#lease_term").val(difference_string);
                }

                $('#accural_period').datepicker({
                    dateFormat: "dd-M-yy",
                });

                // $( "#accural_period" ).datepicker( "option", "disabled", true );

                $('#lease_free_period').on('keyup',function(){

                    var startDate       = $('#lease_start_date').datepicker('getDate');
                    var newdate = new Date(startDate);
                    newdate.setDate(startDate.getDate() + parseInt($(this).val()));
                    $('#accural_period').datepicker('setDate', newdate);
                    //set the minimum date for the lease end date as well

                    //@todo check for the accural_period if that is prior to jan 01, 2019 than show the accounting period fields



                    var dt2 = $('#lease_end_date');
                    dt2.datepicker('option', 'minDate', newdate);

                    $('#lease_term').val('');
                    $('#lease_end_date').val('');
                });
            });

        });
    </script>
@endsection