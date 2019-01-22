@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Underlying Lease Asset Complete Details</div>

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
                                <label for="other_details" class="col-lg-4 col-md-6 control-label">Any other Details of the Underlying Lease Asset</label>
                                <div class="col-lg-4 col-md-6">
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
                                <label for="country" class="col-lg-4 col-md-6 control-label">Country</label>
                                <div class="col-lg-4 col-md-6">
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
                                <label for="location" class="col-lg-4 col-md-6 control-label">Place Where Located</label>
                                <div class="col-lg-4 col-md-6">
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
                                <label for="specific_use" class="col-lg-4 col-md-6 control-label">Specific Use of the Lease Asset</label>
                                <div class="col-lg-4 col-md-6">
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
                                <label for="use_of_asset" class="col-lg-4 col-md-6 control-label">State Use Of Asset</label>
                                <div class="col-lg-4 col-md-6">
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
                                <label for="expected_life" class="col-lg-4 col-md-6 control-label">Specific Use of the Lease Asset</label>
                                <div class="col-lg-4 col-md-6">
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


                        <fieldset class="scheduler-border" id="leaseterm">
                            <legend class="scheduler-border">Lease Term of the Underlying Lease Asset</legend>
                            <div class="form-group{{ $errors->has('lease_start_date') ? ' has-error' : '' }} required">
                                <label for="lease_start_date" class="col-lg-4 col-md-6 control-label">Lease Start Date</label>
                                <div class="col-lg-4 col-md-6">
                                    <input id="lease_start_date" type="text" placeholder="Lease Start Date" class="form-control" name="lease_start_date" value="{{ old('lease_start_date', $asset->lease_start_date) }}" autocomplete="off">
                                    @if ($errors->has('lease_start_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_start_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('lease_free_period') ? ' has-error' : '' }} required">
                                <label for="lease_free_period" class="col-lg-4 col-md-6 control-label">Initial Lease Free Period, If any(In Days)</label>
                                <div class="col-lg-4 col-md-6">
                                    <input id="lease_free_period" type="text" placeholder="Number of Days" class="form-control" name="lease_free_period" value="{{ old('lease_free_period', $asset->lease_free_period) }}" >
                                    @if ($errors->has('lease_free_period'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_free_period') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('accural_period') ? ' has-error' : '' }} required">
                                <label for="accural_period" class="col-lg-4 col-md-6 control-label">Start Date of Lease Payment / Accrual Period</label>
                                <div class="col-lg-4 col-md-6">
                                    <input id="accural_period" type="text" placeholder="Start Date of Lease Payment / Accrual Period" class="form-control" name="accural_period" value="{{ old('accural_period', $asset->accural_period) }}" readonly="readonly" style="pointer-events: none">
                                    @if ($errors->has('accural_period'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('accural_period') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('lease_end_date') ? ' has-error' : '' }} required">
                                <label for="lease_end_date" class="col-lg-4 col-md-6 control-label">Lease End Date, Non-Cancellable Period</label>
                                <div class="col-lg-4 col-md-6">
                                    <input id="lease_end_date" type="text" placeholder="Lease End Date, Non-Cancellable Period" class="form-control" name="lease_end_date" value="{{ old('lease_end_date', $asset->lease_end_date) }}" autocomplete="off">
                                    @if ($errors->has('lease_end_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_end_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('lease_term') ? ' has-error' : '' }} required">
                                <label for="lease_term" class="col-lg-4 col-md-6 control-label">Lease Term (in Months & Years)</label>
                                <div class="col-lg-4 col-md-6">
                                    <input id="lease_term" type="text" placeholder="Lease Term (in Months & Years)" class="form-control" name="lease_term" value="{{ old('lease_term', $asset->lease_term) }}" readonly="readonly">
                                    @if ($errors->has('lease_term'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_term') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </fieldset>

                        <fieldset class="scheduler-border" id="prior_accounting" style="display: none">
                            <legend class="scheduler-border" >Lease Asset Accounting Adopted Prior to 2019</legend>
                            <div class="form-group{{ $errors->has('accounting_treatment') ? ' has-error' : '' }} required">
                                <label for="accounting_treatment" class="col-lg-4 col-md-6 control-label">Lease Asset Accounting Treatment Followed Upto 2018</label>
                                <div class="col-lg-4 col-md-6">
                                    <select name="accounting_treatment" class="form-control" id="accounting_treatment">
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

                        <fieldset class="scheduler-border using_lease_payment" style="display:none;">
                            <legend class="scheduler-border">Lease Payment Use</legend>
                            <div class="form-group{{ $errors->has('using_lease_payment') ? ' has-error' : '' }} required using_lease_payment" style="display: block">
                                <label for="variable_amount_determinable" class="col-lg-4 col-md-5 control-label">Using Lease Payment</label>
                                <div class="col-lg-5 col-md-6">

                                    <div class="col-md-12 form-check form-check-inline">
                                        <input class="form-check-input" name="using_lease_payment" type="checkbox" id="yes" value="1" @if(old('using_lease_payment' ,$asset->using_lease_payment) == "1") checked="checked" @endif>
                                        <label class="form-check-label" for="1" style="vertical-align: 4px">Current Lease Payment as on Jan 01, 2019</label>
                                    </div>

                                    <div class=" col-md-12 form-check form-check-inline">
                                        <input class="form-check-input" name="using_lease_payment" type="checkbox" id="no" value="2" @if(old('using_lease_payment',$asset->using_lease_payment) == "2") checked="checked" @endif>
                                        <label class="form-check-label" for="2" style="vertical-align: 4px">Initial Lease Payment as on First Lease Start</label>
                                    </div>
                                    @if ($errors->has('using_lease_payment'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('using_lease_payment') }}</strong>
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
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
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




                $("input[type='checkbox']").on('click', function(){
                    var group = "input[name='"+$(this).attr("name")+"']";
                    $(group).prop("checked",false);
                    $(this).prop("checked",true);
                });


                function toggleUsinLeasePayment(){
                    var _start_date =  $('#accural_period').datepicker('getDate');
                    if(_start_date < new Date('January 01 2019')){
                        $('.using_lease_payment').show();

                        $('#prior_accounting').show();

                    } else {
                        $('.using_lease_payment').hide();

                        $('#prior_accounting').hide();
                        $('#accounting_treatment').val('');
                    }


                }

                $('input[name="using_lease_payment"]').on('click', function(){
                    if($(this).is(":checked") && $(this).val() == '1'){
                        var message = "You are required to place escalation rates if applicable, effective from 2019.";
                    } else {
                        var message = "You are required to place escalation rates if applicable, effective from the Lease Start Date.";
                    }

                    bootbox.alert(message);
                });


                $("#lease_start_date").datepicker({
                    dateFormat: "dd-M-yy",
                    changeYear : true,
                    @if($min_year && $min_year->max_previous_lease_start_year)
                            minDate: new Date('{{ \Carbon\Carbon::create($min_year->max_previous_lease_start_year, 1, 1)->format('Y-m-d') }}'),
                    @endif
                    onSelect: function () {
                        var dt2 = $('#lease_end_date');
                        var startDate = $(this).datepicker('getDate');
                        //add 30 days to selected date
                        startDate.setDate(startDate.getDate() + 30);
                        var minDate = $(this).datepicker('getDate');
                        var dt2Date = dt2.datepicker('getDate');
                        //difference in days. 86400 seconds in day, 1000 ms in second
                        var dateDiff = (dt2Date - minDate)/(86400 * 1000);

                        // dt2.datepicker('option', 'minDate', minDate);

                        setTimeout(function(){
                            resetAllDates();
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
                    onSelect : function(){

                    }
                });

                toggleUsinLeasePayment();

                $('#lease_free_period').on('keyup',function(){
                    resetAllDates();
                });

                function resetAllDates(){
                    var startDate       = $('#lease_start_date').datepicker('getDate');
                    var newdate = new Date(startDate);
                    newdate.setDate(startDate.getDate() + parseInt($('#lease_free_period').val()));
                    $('#accural_period').datepicker('setDate', newdate);
                    //set the minimum date for the lease end date as well

                    //lease end date should be +30 days of accural peroid date
                    var dt2 = $('#lease_end_date');
                    var endDate = $('#lease_end_date').datepicker('getDate');
                    var dt3 = new Date($('#accural_period').datepicker('getDate'));
                    var dt4 = new Date(dt3.setDate(dt3.getDate() + 30));
                    dt2.datepicker('option', 'minDate', dt4);

                    @if(old('lease_end_date', $asset->lease_end_date))
                        dt2.datepicker('setDate', new Date('{{ $asset->lease_end_date }}'));
                    @endif

                    //@todo check for the accural_period if that is prior to jan 01, 2019 than show the accounting period fields
                    toggleUsinLeasePayment();
                }
            });

            $('select[name="accounting_treatment"]').on('change', function () {
                 var lease_asset_accounting = $("#accounting_treatment").find('option:selected').text();
                 if(lease_asset_accounting == 'Finance Lease Accounting'){
                var modal = bootbox.dialog({
                    message: "Finance Lease will be revalued at present value as on Jan 01, 2019",
                    buttons: [
                      {
                        label: "OK",
                        className: "btn btn-success pull-left",
                        callback: function() {
                        }
                      },
                     
                    ],
                    show: false,
                    onEscape: function() {
                      modal.modal("hide");
                    }
                });

               modal.modal("show");
             }
            });
        });
    </script>
@endsection