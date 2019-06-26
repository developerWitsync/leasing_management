<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="categoriesOuter clearfix">
        <div class="categoriesHd">Type of Lease Payments</div>
        <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} required">
            <label for="name" class="col-md-12 control-label">Name of Lease Payment</label>
            <div class="col-md-12">
                <input id="name" type="text" placeholder="Name" class="form-control" name="name"
                       value="{{ old('name', $payment->name) }}"
                       @if($subsequent_modify_required) disabled="disabled" @endif>
                @if ($errors->has('name'))
                    <span class="help-block">
                        <strong>{{ $errors->first('name') }}</strong>
                    </span>
                @endif

                @if($subsequent_modify_required)
                    <input type="hidden" name="name" value="{{$payment->name}}">
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('type') ? ' has-error' : '' }} required">
            <label for="type" class="col-md-12 control-label">Type of Lease Payment</label>
            <div class="col-md-12">
                <select name="type" id="leaseType" class="form-control"
                        @if($subsequent_modify_required) disabled="disabled" @endif>
                    <option value="">--Select Lease Payment Type--</option>
                    @foreach($lease_payments_types as $lease_payments_type)
                        @if($lease->lease_type_id == 1 && $lease_payments_type->id == 1)
                            <option value="{{ $lease_payments_type->id }}"
                                    @if(old('type', $payment->type) == $lease_payments_type->id) selected="selected" @endif>{{ $lease_payments_type->title }}</option>
                            @php break; @endphp
                        @else
                            <option value="{{ $lease_payments_type->id }}"
                                    @if(old('type', $payment->type) == $lease_payments_type->id) selected="selected" @endif>{{ $lease_payments_type->title }}</option>
                        @endif
                    @endforeach
                </select>
                @if ($errors->has('type'))
                    <span class="help-block">
                        <strong>{{ $errors->first('type') }}</strong>
                    </span>
                @endif

                @if($subsequent_modify_required)
                    <input type="hidden" name="type" value="{{$payment->type}}">
                @endif

            </div>
        </div>

        <div class="form-group{{ $errors->has('nature') ? ' has-error' : '' }} required">
            <label for="type" class="col-md-12 control-label">Nature of Lease Payment</label>
            <div class="col-md-12">
                <select name="nature" class="form-control" @if($subsequent_modify_required) disabled="disabled" @endif>
                    <option value="">--Select Lease Payment Nature--</option>
                    @foreach($lease_payments_nature as $nature)
                        <option value="{{ $nature->id}}"
                                @if(old('nature',$payment->nature) == $nature->id) selected="selected" @endif>{{ $nature->title }}</option>
                    @endforeach
                </select>
                @if ($errors->has('nature'))
                    <span class="help-block">
                        <strong>{{ $errors->first('nature') }}</strong>
                    </span>
                @endif

                @if($subsequent_modify_required)
                    <input type="hidden" name="nature" value="{{$payment->nature}}">
                @endif

            </div>
        </div>

        <div class="form-group{{ $errors->has('variable_basis') ? ' has-error' : '' }} required variable_basis"
             @if(old('nature',$payment->nature) == "2") @else style="display: none" @endif>
            <label for="variable_basis" class="col-md-12 control-label">Variable Basis</label>
            <div class="col-md-12">
                <select name="variable_basis" class="form-control">
                    <option value="">--Select Variable Basis--</option>
                    @foreach($variable_basis as $basis)
                        <option value="{{ $basis->id}}"
                                @if(old('variable_basis',$payment->variable_basis) == $basis->id) selected="selected" @endif>{{ $basis->title }}</option>
                    @endforeach
                </select>
                @if ($errors->has('variable_basis'))
                    <span class="help-block">
                        <strong>{{ $errors->first('variable_basis') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('variable_amount_determinable') ? ' has-error' : '' }} required variable_basis"
             @if(old('nature',$payment->nature) == "2") @else style="display: none" @endif>
            <label for="variable_amount_determinable" class="col-md-12 control-label">Variable Amount
                Determinable</label>
            <div class="col-md-12">

                <div class="col-md-12 form-check form-check-inline">
                    <input class="form-check-input" name="variable_amount_determinable" type="checkbox" id="yes"
                           value="yes"
                           @if(old('variable_amount_determinable',$payment->variable_amount_determinable) == "yes") checked="checked" @endif>
                    <label class="form-check-label" for="yes" style="vertical-align: 4px">Yes</label>
                </div>

                <div class=" col-md-12 form-check form-check-inline">
                    <input class="form-check-input" name="variable_amount_determinable" type="checkbox" id="no"
                           value="no"
                           @if(old('variable_amount_determinable',$payment->variable_amount_determinable) == "no") checked="checked" @endif>
                    <label class="form-check-label" for="no" style="vertical-align: 4px">No</label>
                </div>
            </div>
        </div>

        <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
            <label for="description" class="col-md-12 control-label">Any Other Description</label>
            <div class="col-md-12">
                <input id="description" type="text" placeholder="Description" class="form-control" name="description"
                       value="{{ old('description',$payment->description) }}">
                @if ($errors->has('description'))
                    <span class="help-block">
                        <strong>{{ $errors->first('description') }}</strong>
                    </span>
                @endif
            </div>
        </div>

    </div>

    <div class="categoriesOuter clearfix variable_basis_amount_determinable">
        <div class="categoriesHd">
            Lease Payment Periods
            @if($subsequent_modify_required)
                | <span class="badge badge-primary"
                        style="background-color: #f2dede;border-color: #ebccd1;color: #a94442;">Please re-update the lease payment dates starting on or after subsequent modification date.</span>
            @endif
        </div>
        <div class="form-group{{ $errors->has('payment_interval') ? ' has-error' : '' }} required">
            <label for="payment_interval" class="col-md-12 control-label">Lease Payment Interval</label>
            <div class="col-md-12">
                <select name="payment_interval" class="form-control">
                    <option value="">--Select Payment Interval--</option>
                    @foreach($payments_frequencies as $frequency)
                        @php
                            $disabled_option = "";
                        @endphp
                        @if($frequency->id > 1 && $frequency->id!= 6)
                            @if($frequency->id == 2 && $lease_span_time_in_days < 1)
                                @php
                                    $disabled_option = 'disabled="disabled"';
                                @endphp
                            @elseif($frequency->id == 3 && $lease_span_time_in_days < 3)
                                @php
                                    $disabled_option = 'disabled="disabled"';
                                @endphp
                            @elseif($frequency->id == 4 && $lease_span_time_in_days < 6)
                                @php
                                    $disabled_option = 'disabled="disabled"';
                                @endphp
                            @elseif($frequency->id == 5 && $lease_span_time_in_days < 12)
                                @php
                                    $disabled_option = 'disabled="disabled"';
                                @endphp
                            @endif

                        @endif
                        <option {{ $disabled_option }} value="{{ $frequency->id }}"
                                @if(old('payment_interval',$payment->payment_interval) == $frequency->id) selected="selected" @endif>{{ $frequency->title }}</option>
                    @endforeach
                </select>

                @if ($errors->has('payment_interval'))
                    <span class="help-block">
                        <strong>{{ $errors->first('payment_interval') }}</strong>
                    </span>
                @endif

            </div>
        </div>

        <div class="form-group{{ $errors->has('payout_time') ? ' has-error' : '' }} required">
            <label for="payout_time" class="col-md-12 control-label">Lease Payment Payout Time</label>
            <div class="col-md-12">
                <select name="payout_time" class="form-control">
                    <option value="">--Select Payment Payout Time--</option>
                    @foreach($payments_payout_times as $payout_time)
                        <option value="{{ $payout_time->id }}"
                                @if(old('payout_time',$payment->payout_time) == $payout_time->id) selected="selected" @endif>{{ $payout_time->title }}</option>
                    @endforeach
                </select>
                @if ($errors->has('payout_time'))
                    <span class="help-block">
                        <strong>{{ $errors->first('payout_time') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('first_payment_start_date') ? ' has-error' : '' }} required">
            <label for="first_payment_start_date" class="col-md-12 control-label">
                @if(\Carbon\Carbon::parse($asset->accural_period)->lessThan(\Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)))
                    First Lease Payment Start Date (Effective from Year 2019)
                @else
                    First Lease Payment Start Date
                @endif
            </label>
            <div class="col-md-12">
                <input id="first_payment_start_date" type="text" placeholder="First Lease Payment Start Date"
                       class="form-control lease_period1" name="first_payment_start_date"
                       @if($subsequent_modify_required)
                       value="{{ old('first_payment_start_date',$lease->modifyLeaseApplication->last()->effective_from) }}"
                       @else
                       value="{{ old('first_payment_start_date',$payment->first_payment_start_date) }}"
                       @endif
                       autocomplete="off" readonly="true" style="background-color: #fff;">

                @if ($errors->has('first_payment_start_date'))
                    <span class="help-block">
                            <strong>{{ $errors->first('first_payment_start_date') }}</strong>
                        </span>
                @endif

            </div>
        </div>

        <div class="form-group{{ $errors->has('last_payment_end_date') ? ' has-error' : '' }} required">
            <label for="last_payment_end_date" class="col-md-12 control-label">Last Lease Payment End Date</label>
            <div class="col-md-12">
                <input id="last_payment_end_date" type="text" placeholder="Last Lease Payment End Date"
                       class="form-control lease_period2" name="last_payment_end_date"
                       value="{{ old('last_payment_end_date',$payment->last_payment_end_date) }}" autocomplete="off">
                @if ($errors->has('last_payment_end_date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('last_payment_end_date') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('altered_payment_due_date.0') || $errors->has('due_dates_confirmed') ? ' has-error' : '' }} show_annexure"
             style="width: 98%">
            <div class="col-md-12">
                <a href="javascript:void(0);" class="btn btn-primary confirm_lease_payment_due_dates">Confirm Lease
                    Payment Due Dates</a>
                <a href="javascript:void(0);" class="btn btn-sm btn-warning view_current_edited_dates pull-right"
                   style="display: none">View Selected Dates</a>
                @if($payment->payment_interval == 6)
                    <a href="javascript:void(0);"
                       class="btn btn-warning view_current_lease_payment_due_dates pull-right">View
                        Current Dates</a>
                @endif
                @if(!empty($payout_due_dates) && !$subsequent_modify_required)
                    <input type="hidden" value="1" name="due_dates_confirmed">
                @else
                    <input type="hidden" value="0" name="due_dates_confirmed">
                @endif

                @if ($errors->has('due_dates_confirmed'))
                    <span class="help-block">
                        <strong>{{ $errors->first('due_dates_confirmed') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="categoriesOuter clearfix immediate_previous_lease_end_date" style="@if(old('payout_time', $payment->payout_time) == 2) @else display: none @endif">

        @if(\Carbon\Carbon::parse($asset->lease_start_date)->greaterThanOrEqualTo(getParentDetails()->accountingStandard->base_date))
        <div class="categoriesHd">
            First Lease Interval Start Date
        </div>
        @else
        <div class="categoriesHd">
            Immediate Previous Lease End Date
        </div>
        @endif
        <div class="form-group{{ $errors->has('immediate_previous_lease_end_date') ? ' has-error' : '' }} required">
            <label for="immediate_previous_lease_end_date" class="col-md-12 control-label">Immediate Previous Lease End Date</label>
            <div class="col-md-12">
                <input id="immediate_previous_lease_end_date" type="text" placeholder="Last Lease Payment End Date"
                       class="form-control lease_period2" name="immediate_previous_lease_end_date"
                       value="{{ old('immediate_previous_lease_end_date',$payment->immediate_previous_lease_end_date) }}" autocomplete="off">
                @if ($errors->has('immediate_previous_lease_end_date'))
                    <span class="help-block">
                    <strong>{{ $errors->first('immediate_previous_lease_end_date') }}</strong>
                </span>
                @endif
            </div>
        </div>
    </div>

    <div class="categoriesOuter clearfix last_lease_payment_interval_end_date" style="@if(old('payout_time', $payment->payout_time) == 2) display: none @else  @endif">
        <div class="categoriesHd">
            Last Lease Payment Interval End Date
        </div>
        <div class="form-group{{ $errors->has('last_lease_payment_interval_end_date') ? ' has-error' : '' }} required">
            <label for="last_lease_payment_interval_end_date" class="col-md-12 control-label">Last Lease Payment Interval End Date</label>
            <div class="col-md-12">
                <input id="last_lease_payment_interval_end_date" type="text" placeholder="Last Lease Payment Interval End Date"
                       class="form-control lease_period2" name="last_lease_payment_interval_end_date"
                       value="{{ old('last_lease_payment_interval_end_date',$payment->last_lease_payment_interval_end_date) }}" autocomplete="off">
                @if ($errors->has('last_lease_payment_interval_end_date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('last_lease_payment_interval_end_date') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="categoriesOuter clearfix variable_basis_amount_determinable">
        <div class="categoriesHd">
            Lease Payments
            @if($subsequent_modify_required)
                | <span class="badge badge-primary"
                        style="background-color: #f2dede;border-color: #ebccd1;color: #a94442;">Please re-update the lease payment per interval per unit existing as on subsequent modification date.</span>
            @endif
        </div>
        <div class="form-group{{ $errors->has('payment_currency') ? ' has-error' : '' }} required">
            <label for="payment_currency" class="col-md-12 control-label">Lease Payment Currency</label>
            <div class="col-md-12">
                <input id="payment_currency" type="text" placeholder="Lease Payment Currency" class="form-control"
                       name="payment_currency" value="{{ $lease->lease_contract_id }}" readonly="readonly"
                       @if($subsequent_modify_required) disabled="disabled" @endif>
                @if ($errors->has('payment_currency'))
                    <span class="help-block">
                        <strong>{{ $errors->first('payment_currency') }}</strong>
                    </span>
                @endif

                @if($subsequent_modify_required)
                    <input type="hidden" name="payment_currency" value="{{ $lease->lease_contract_id }}">
                @endif

            </div>
        </div>

        <div class="form-group{{ $errors->has('similar_chateristics_assets') ? ' has-error' : '' }} required">
            <label for="similar_chateristics_assets" class="col-md-12 control-label">Number of Units of Lease Assets of
                Similar Characteristics</label>
            <div class="col-md-12">
                <input id="similar_chateristics_assets" type="text"
                       placeholder="Number of Units of Lease Assets of Similar Characteristics" class="form-control"
                       name="similar_chateristics_assets" value="{{ $asset->similar_asset_items }}" readonly="readonly"
                       @if($subsequent_modify_required) disabled="disabled" @endif>
                @if ($errors->has('similar_chateristics_assets'))
                    <span class="help-block">
                        <strong>{{ $errors->first('similar_chateristics_assets') }}</strong>
                    </span>
                @endif

                @if($subsequent_modify_required)
                    <input type="hidden" name="similar_chateristics_assets" value="{{ $asset->similar_asset_items }}">
                @endif

            </div>
        </div>

        <div class="form-group{{ $errors->has('lease_payment_per_interval') ? ' has-error' : '' }} required">
            <label for="lease_payment_per_interval" class="col-md-12 control-label">Lease Payment Per Interval</label>
            <div class="col-md-12">

                <select class="form-control" name="lease_payment_per_interval"
                        @if($subsequent_modify_required) disabled="disabled" @endif>
                    <option value="">--Select Interval Nature--</option>
                    <option value="1"
                            @if(old('lease_payment_per_interval', $payment->lease_payment_per_interval) == '1') selected="selected" @endif>
                        Consistent Interval To Interval
                    </option>
                    <option value="2"
                            @if(old('lease_payment_per_interval', $payment->lease_payment_per_interval) == '2') selected="selected" @endif>
                        Inconsistent Interval to Interval
                    </option>
                </select>

                @if ($errors->has('lease_payment_per_interval'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lease_payment_per_interval') }}</strong>
                    </span>
                @endif

                @if($subsequent_modify_required)
                    <input type="hidden" name="lease_payment_per_interval"
                           value="{{ $payment->lease_payment_per_interval }}">
                @endif

            </div>
        </div>


        <div class="import_via_excel import_option_first clearfix"
             @if(old('lease_payment_per_interval', $payment->lease_payment_per_interval) == '2') style="display: block"
             @else style="display:none;" @endif>
            <div class="headerImport">
                <h1>Option 1</h1>
            </div>
            <div class="bodyImport">
                <div class="first">
                    You can download the excel template to enter the lease payments against each lease payment dates as
                    confirmed above and upload the template with the lease payments.
                </div>
                <div class="second">
                    <strong>Please Note:</strong> Do not change the lease payment dates in the excel template, any
                    change in lease dates should be updated first under above section on Lease Payment Periods only.
                    Further, if you have entered lease payments in this template including lease escalation then do not
                    re-apply escalation under Step 09 else apply escalation under Step 09.
                </div>

                <div class="import_via_excel"
                     @if(old('lease_payment_per_interval', $payment->lease_payment_per_interval) == '2') style="display: block;padding-bottom: 30px;"
                     @else style="display:none;padding-bottom: 30px;" @endif>
                    <label class="col-md-12 control-label">&nbsp;</label>
                    <div class="row">
                        <div class="col-md-7">
                            <button class="btn btn-primary download_excel_for_import"><i class="fa fa-download"></i>
                                Download
                                Excel For Importing
                            </button>
                        </div>

                        <div class="col-md-5">
                            <div class=" frmattachFile">
                                <input type="name" id="uploadX" name="name" class="form-control" disabled="disabled">
                                <button type="button" class="browseBtn"><i class="fa fa-upload"></i> Browse</button>
                                <input type="file" id="import_dates" name="file" class="fileType">
                                <h6 class="disabled">Only Xlsx with 2MB size of files are allowed.</h6>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="import_via_excel import_option_first clearfix"
             @if(old('lease_payment_per_interval', $payment->lease_payment_per_interval) == '2') style="display: block"
             @else style="display:none;" @endif>
            <div class="headerImport">
                <h1>Option 2</h1>
            </div>
            <div class="bodyImport">
                <div class="first">
                    Manually enter lease payments in the below table against each lease payment dates. Please use this
                    option only when option 1 is not applied.
                </div>

                <div class="form-group incpi @if(old('lease_payment_per_interval', $payment->lease_payment_per_interval) == '2') @else hidden @endif"
                     style="width: 98%;padding: 15px;">

                </div>

            </div>


        </div>


        <div class="form-group{{ $errors->has('payment_per_interval_per_unit') ? ' has-error' : '' }} cpi @if(old('lease_payment_per_interval', $payment->lease_payment_per_interval) == '1') @else hidden @endif required">
            <label for="payment_per_interval_per_unit" class="col-md-12 control-label">Lease Payment Per Interval Per
                Unit</label>
            <div class="col-md-12">
                <input id="payment_per_interval_per_unit" type="text" placeholder="" class="form-control"
                       name="payment_per_interval_per_unit"
                       value="{{ old('payment_per_interval_per_unit',$payment->payment_per_interval_per_unit) }}">
                @if ($errors->has('payment_per_interval_per_unit'))
                    <span class="help-block">
                        <strong>{{ $errors->first('payment_per_interval_per_unit') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('total_amount_per_interval') ? ' has-error' : '' }} cpi @if(old('lease_payment_per_interval', $payment->lease_payment_per_interval) == '1') @else hidden @endif required">
            <label for="total_amount_per_interval" class="col-md-12 control-label">Total Lease Amount Per
                Interval</label>
            <div class="col-md-12">
                <input id="total_amount_per_interval" type="text" placeholder="" class="form-control"
                       name="total_amount_per_interval"
                       value="{{ old('total_amount_per_interval',$payment->total_amount_per_interval) }}"
                       readonly="readonly">
                @if ($errors->has('total_amount_per_interval'))
                    <span class="help-block">
                        <strong>{{ $errors->first('total_amount_per_interval') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('attachment') ? ' has-error' : '' }} cpi @if(old('lease_payment_per_interval', $payment->lease_payment_per_interval) == '1') @else hidden @endif">
            <label for="workings_doc" class="col-md-12 control-label">Upload Any Workings</label>
            <div class="col-md-12 frmattachFile">
                <input type="name" id="upload" name="name" class="form-control" disabled="disabled">
                <button type="button" class="browseBtn">Browse</button>
                <input id="workings_doc" type="file" placeholder="" class="fileType" name="attachment">
                <h6 class="disabled">{{ config('settings.file_size_limits.file_validation') }}</h6>
                @if ($errors->has('attachment'))
                    <span class="help-block">
                        <strong>{{ $errors->first('attachment') }}</strong>
                    </span>
                @endif
            </div>
            @if($payment->attachment !='')
                <a href="{{asset('uploads/'.$payment->attachment)}}" class="downloadIcon"
                   target="_blank"><i class="fa fa-download"></i></a>
            @endif
        </div>


        <span class="hidden altered_payment_due_dates">
            @if(!empty($payout_due_dates))
                @foreach($payout_due_dates as $date)
                    <input type="hidden" class="altered_payment_due_date" name="altered_payment_due_date[]"
                           value="{{ $date }}">
                @endforeach
            @else
                <input type="hidden" class="altered_payment_due_date" name="altered_payment_due_date[]" value="">
            @endif
        </span>
    </div>


    <div class="form-group btnMainBx">

        <div class="col-md-6 col-sm-6 btn-backnextBx">
            <a href="{{ route('addlease.payments.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>
        </div>
        <div class="col-md-6 btnsubmitBx">
            <button type="submit" class="btn btn-success" name="submit" value="save"
                    onclick="showOverlayForAjax();">Save
            </button>
        </div>
    </div>


</form>

<!--Payment Due Dates Annexure Modal Start here -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="dialog">
        <div class="modal-content annexure_modal_body">

        </div>
    </div>
</div>

<!--Payment Due Dates Annexure Modal End here -->
@section('footer-script')
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script>

        $(function () {

            $('#import_dates').change(function () {
                $('#import_dates').show();
                var filename = $('#import_dates').val();
                var or_name = filename.split("\\");
                $('#uploadX').val(or_name[or_name.length - 1]);
            });

            removeOverlayAjax();

            // variable_basis_amount_determinable
            @if(old('nature', $payment->nature) == "2" && old('variable_amount_determinable', $payment->variable_amount_determinable) == "no")
            $('.variable_basis_amount_determinable').hide();
            @endif

            $('select[name="lease_payment_per_interval"]').on('change', function () {
                var selected_value = $(this).val();
                if (selected_value == "1") {
                    $('.cpi').removeClass('hidden');
                    $('.incpi').addClass('hidden');
                    $('.incpi').html('');
                    $(".import_via_excel").hide();
                } else if (selected_value == "2") {
                    $('.cpi').addClass('hidden');
                    $('.incpi').removeClass('hidden');
                    $('.incpi').html('');
                    showPaymentAnnexureForUpdate();
                } else {
                    $('.cpi').addClass('hidden');
                    $('.incpi').addClass('hidden');
                    $('.incpi').html('');
                    $(".import_via_excel").hide();
                }
            });

            function showPaymentAnnexureForUpdate() {
                $paymentDates = $("input[name='altered_payment_due_date[]']")
                    .map(function () {
                        return $(this).val();
                    }).get();


                $asset_id = '{{ $asset->id }}';
                $lease_id = '{{ $asset->lease->id }}';

                $.ajax({
                    url: "{{ route('addlease.payments.inconsitentperintervalannexure') }}",
                    type: 'post',
                    data: {
                        paymentDates: $paymentDates,
                        lease_id: $lease_id,
                        asset_id: $asset_id
                    },
                    dataType: 'text',
                    beforeSend: function () {
                        $(".import_via_excel").show();
                    },
                    success: function (response) {
                        $('.incpi').html(response);
                    }
                })
            }

            /**
             * Create and download the excel on the system...
             */
            $('.download_excel_for_import').on('click', function (e) {
                e.preventDefault();
                $paymentDates = $("input[name='altered_payment_due_date[]']")
                    .map(function () {
                        return $(this).val();
                    }).get();
                $asset_id = '{{ $asset->id }}';
                $lease_id = '{{ $asset->lease->id }}';

                $.ajax({
                    url: "{{ route('addlease.payments.inconsistent_download_excel') }}",
                    type: "post",
                    data: {
                        paymentDates: $paymentDates,
                        lease_id: $lease_id,
                        asset_id: $asset_id
                    },
                    dataType: 'json',
                    success: function (response) {
                        if (response.status) {
                            window.location.href = '/uploads/' + response.file_link;
                        } else {
                            alert('Something went wrong please go with the manual approach.');
                        }
                    }
                })
            });

            $('#import_dates').change(function () {
                //on change event
                formdata = new FormData();
                if ($(this).prop('files').length > 0) {
                    file = $(this).prop('files')[0];
                    formdata.append("import_excel", file);
                }

                $paymentDates = $("input[name='altered_payment_due_date[]']")
                    .map(function () {
                        return $(this).val();
                    }).get();

                $asset_id = '{{ $asset->id }}';
                $lease_id = '{{ $asset->lease->id }}';
                formdata.append("paymentDates", JSON.stringify($paymentDates));
                formdata.append("asset_id", $asset_id);
                formdata.append("lease_id", $lease_id);

                $.ajax({
                    url: '{{ route('addlease.payments.inconsistent_import_excel') }}',
                    type: "POST",
                    data: formdata,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        showOverlayForAjax();
                    },
                    success: function (result) {
                        removeOverlayAjax();
                        if (result.status) {
                            $.each(result.paymentDates, function (i, e) {
                                var input_name = "input[name='inconsistent_date_payment[" + e[0] + "]']";
                                var value = e[1];
                                if (value == '' || value == null) {
                                    value = 0;
                                }
                                $(input_name).val(value);
                            });
                        } else {
                            if (typeof (result.message) != 'undefined') {
                                var message;
                                bootbox.alert({
                                    message: result.message,
                                    buttons: {
                                        ok: {
                                            label: 'Ok'
                                        }
                                    }
                                });
                            } else {
                                alert('Something went wrong please go with the manual approach.');
                            }
                        }
                    }
                });
            });


            @if($payment->id)
            $.ajax({
                url: "{{ route('addlease.payments.loadinconsistentannexure', ['payment_id' => $payment->id]) }}",
                type: 'get',
                dataType: 'text',
                success: function (response) {
                    $('.incpi').html(response);
                }
            });
            @endif
        });

        $('#leaseType').change(function () {
            var optvalue = $(this).val();
            if (optvalue == 1) {
                bootbox.alert({
                    message: 'Lease payments which are part of Lease Component shall be considered for Lease valuation and accordingly be capitalized.',
                    buttons: {
                        ok: {
                            label: 'Confirm'
                        }
                    }
                });
            }
            if (optvalue == 2) {
                bootbox.alert({
                    message: 'Lease payments which are part of Non-Lease Component shall be expensed and shall not be considered here for Lease Valuation.',
                    buttons: {
                        ok: {
                            label: 'Confirm'
                        }
                    }
                });
            }
            if (optvalue == 3) {
                bootbox.alert({
                    message: 'When Non-Lease Component combined with Lease Component, such Lease payments accounted as Single Lease Component and shall be considered for Lease valuation and accordingly will be Capitalized.',
                    buttons: {
                        ok: {
                            label: 'Confirm'
                        }
                    }
                });
            }
            return false;
        });


        $("#first_payment_start_date").datepicker({
            dateFormat: "dd-M-yy",
            changeYear: true,
            changeMonth: true,

            @if($subsequent_modify_required || $is_subsequent)
            minDate: new Date('{{ $lease->modifyLeaseApplication->last()->effective_from }}'),
            @else
            @if($asset->using_lease_payment == '1')
            //cannpt go before the base date...
            minDate: new Date('{{ getParentDetails()->accountingStandard->base_date }}'),
            @else
            //cannot go before the lease start date..
            minDate: new Date('{{ $asset->accural_period }}'),
            @endif
                    @endif

            maxDate: new Date('{{ $asset->getLeaseEndDate($asset) }}'),

            {!!  getYearRanage() !!}
            onSelect: function (date, instance) {
                var _ajax_url = '{{route("lease.checklockperioddate")}}';
                checklockperioddate(date, instance, _ajax_url);
            }
        });

        $("#immediate_previous_lease_end_date").datepicker({
            dateFormat: "dd-M-yy",
            changeYear: true,
            changeMonth: true,
            maxDate: new Date($("#first_payment_start_date").val()),
            @if($subsequent_modify_required || $is_subsequent)
            minDate: new Date('{{ $lease->modifyLeaseApplication->last()->effective_from }}'),
            @else
            @if($asset->using_lease_payment == '1')
            //cannpt go before the base date...
            minDate: new Date('{{ getParentDetails()->accountingStandard->base_date }}'),
            @else
            //cannot go before the lease start date..
            minDate: new Date('{{ $asset->accural_period }}'),
            @endif
            @endif
            {!!  getYearRanage() !!}
            onSelect: function (date, instance) {
                {{--var _ajax_url = '{{route("lease.checklockperioddate")}}';--}}
                {{--checklockperioddate(date, instance, _ajax_url);--}}
            }
        });

        $("#last_lease_payment_interval_end_date").datepicker({
            dateFormat: "dd-M-yy",
            changeYear: true,
            changeMonth: true,
            //minDate: new Date($("#last_payment_end_date").val()),
            @if($subsequent_modify_required || $is_subsequent)
            minDate: new Date('{{ $lease->modifyLeaseApplication->last()->effective_from }}'),
            @else
            @if($asset->using_lease_payment == '1')
            //cannpt go before the base date...
            minDate: new Date('{{ getParentDetails()->accountingStandard->base_date }}'),
            @else
            //cannot go before the lease start date..
            minDate: new Date('{{ $asset->accural_period }}'),
            @endif
                    @endif
            maxDate: new Date('{{ ($asset->getLeaseEndDate($asset)) }}'),
            {!!  getYearRanage() !!}
            onSelect: function (date, instance) {
                {{--var _ajax_url = '{{route("lease.checklockperioddate")}}';--}}
                {{--checklockperioddate(date, instance, _ajax_url);--}}
            }
        });

        $("#last_payment_end_date").datepicker({
            dateFormat: "dd-M-yy",
            changeYear: true,
            changeMonth: true,
            @if($subsequent_modify_required || $is_subsequent)
            minDate: new Date('{{ $lease->modifyLeaseApplication->last()->effective_from }}'),
            @else
            @if($asset->using_lease_payment == '1')
            //cannpt go before the base date...
            minDate: new Date('{{ getParentDetails()->accountingStandard->base_date }}'),
            @else
            //cannot go before the lease start date..
            minDate: new Date('{{ $asset->accural_period }}'),
            @endif
                    @endif
            maxDate: new Date('{{ ($asset->getLeaseEndDate($asset)) }}'),
            {!!  getYearRanage() !!}
            onSelect: function (date, instance) {
                var _ajax_url = '{{route("lease.checklockperioddate")}}';
                checklockperioddate(date, instance, _ajax_url);
            }
        });


        //If Variable Basis selected
        $('select[name="nature"]').on('change', function () {
            if ($(this).val() == '2') {
                $('.variable_basis').show();
            } else {
                $('.variable_basis').hide();

                //change the values to null as well
                $('#variable_basis').val('');
                $('input[name="variable_amount_determinable"]').prop("checked", false);
            }
        });


        //amount determinable checkbox in case of the Variable basis

        $('input[name="variable_amount_determinable"]').on('click', function () {
            if ($(this).is(':checked') && $(this).val() == 'yes') {
                bootbox.alert('Are you sure that variable amount is determinable and to the extent of the determinable amount you want to include in the Lease Valuation. If not, then please select “No” else can proceed further.');
                $('.variable_basis_amount_determinable').show();
            } else if ($(this).is(':checked') && $(this).val() == 'no') {
                //need to show the pop up here
                bootbox.alert('Are you sure that variable amount is non-determinable. If so, then non-determinable variable amount will not be recorded here and will be expensed in your books of accounts on actual basis in the relevant incurred period.');
                //need to hide all the other payment details and need to reset all the fields value to null as well..
                $('.variable_basis_amount_determinable').hide();

                $(".variable_basis_amount_determinable input").each(function () {
                    var attr = $(this).attr('readonly');
                    if (typeof attr == typeof undefined) {
                        this.value = "";
                    }
                });

                $(".variable_basis_amount_determinable select").each(function () {
                    var attr = $(this).attr('readonly');
                    if (typeof attr == typeof undefined) {
                        this.value = "";
                    }
                });
            }
        });

        //function to calculate the last lease payment end date
        function calculateLastPaymentEndDate(that, firstPaymentStartDate) {
            var _calculated_last_payment_date = new Date();
            var _selected_payment_interval = parseInt($(that).val());
            var _payout_value = parseInt($('select[name="payout_time"]').val());
            if (_payout_value == 2) {
                @php
                    $calculated_date = \Carbon\Carbon::parse($asset->getLeaseEndDate($asset));
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
                            $lease_end_date = \Carbon\Carbon::parse($asset->getLeaseEndDate($asset))->subMonths(1)->format('D M d Y');
                            $calculated_date = $lease_end_date;
                        @endphp
                            _calculated_last_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    case 3:
                        //means selected option is Quarterly
                        @php
                            $lease_end_date = \Carbon\Carbon::parse($asset->getLeaseEndDate($asset));
                            $calculated_date = $lease_end_date->subMonth(3)->format('D M d Y');
                        @endphp
                            _calculated_last_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    case 4:
                        //means selected option is Semi-Annually
                        @php
                            $lease_end_date = \Carbon\Carbon::parse($asset->getLeaseEndDate($asset));
                            $calculated_date = $lease_end_date->subMonth(6)->format('D M d Y');
                        @endphp
                            _calculated_last_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    case 5:
                        //means selected option is Annually
                        @php
                            $lease_end_date = \Carbon\Carbon::parse($asset->getLeaseEndDate($asset));
                            $calculated_date = $lease_end_date->subMonth(12)->format('D M d Y');
                        @endphp
                            _calculated_last_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    default:
                        break;
                }
            }
            //alert(_calculated_last_payment_date);
            $("#last_payment_end_date").datepicker("setDate", new Date(_calculated_last_payment_date));
        }

        //calculate the First Lease Payment Start Date and Last Lease Payment End Date here
        $('select[name="payout_time"] , select[name="payment_interval"]').on('change', function () {

            var _value = parseInt($('select[name="payout_time"]').val());

            //place the conditions for the immediate_previous_lease_end_date and other fields
            if(_value == 1) {
                //at lease interval start date
                $(".immediate_previous_lease_end_date").hide();
                $(".last_lease_payment_interval_end_date").show();
            } else if(_value == 2) {
                //at lease interval end date
                $(".immediate_previous_lease_end_date").show();
                $(".last_lease_payment_interval_end_date").hide();
            } else {

            }

            var _selected_payment_interval = parseInt($('select[name="payment_interval"]').val());

            @if($subsequent_modify_required || $is_subsequent)
                var _start_date = new Date("{{ date('D M d Y', strtotime($lease->modifyLeaseApplication->last()->effective_from)) }}");
            @else
                var _start_date = new Date("{{ date('D M d Y', strtotime($asset->accural_period)) }}");
            @if($asset->using_lease_payment == '1' && \Carbon\Carbon::parse($asset->accural_period)->lessThan(\Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)))
            //cannot go before the base date...
                var _start_date = new Date("{{ date('D M d Y', strtotime(getParentDetails()->accountingStandard->base_date)) }}");
            @endif
            @endif


            var _end_date = new Date("{{ date('D M d Y', strtotime($asset->getLeaseEndDate($asset))) }}");

            if (_value == "" || _selected_payment_interval == "") {
                return false;
            }

            var _calculated_first_payment_date = new Date();
            if (_value == 1) {
                //means At Lease Interval Start
                _calculated_first_payment_date = _start_date;
            } else {
                //means At Lease Interval End
                switch (_selected_payment_interval) {
                    case 1:
                        _calculated_first_payment_date = _end_date;
                        break;
                    case 2:
                        //means selected option is monthly.
                        @php
                            if($subsequent_modify_required || $is_subsequent){
                                $calculated_date = \Carbon\Carbon::parse($lease->modifyLeaseApplication->last()->effective_from)->addMonth(1)->format('D M d Y');
                            } else {
                                $accural_date = \Carbon\Carbon::parse($asset->accural_period);
                                $calculated_date = $accural_date->addMonth(1)->subDay(1)->format('D M d Y');
                                //need to check for the current or initial payment basis
                                if($asset->using_lease_payment == '1' && \Carbon\Carbon::parse($asset->accural_period)->lessThan(\Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date))){
                                    //cannot go before the base date...
                                    $calculated_date = \Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)->addMonth(1)->format('D M d Y');
                                }
                            }
                        @endphp
                            _calculated_first_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    case 3:
                        //means selected option is Quarterly
                        @php
                            if($subsequent_modify_required || $is_subsequent){
                                $calculated_date = \Carbon\Carbon::parse($lease->modifyLeaseApplication->last()->effective_from)->addMonth(3)->format('D M d Y');
                            } else {
                                $accural_date = \Carbon\Carbon::parse($asset->accural_period);
                                $calculated_date = $accural_date->addMonth(3)->subDay(1)->format('D M d Y');
                                //need to check for the current or initial payment basis
                                if($asset->using_lease_payment == '1' && \Carbon\Carbon::parse($asset->accural_period)->lessThan(\Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date))){
                                    //cannot go before the base date...
                                    $calculated_date = \Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)->addMonth(3)->format('D M d Y');
                                }
                            }
                        @endphp
                            _calculated_first_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    case 4:
                        //means selected option is Semi-Annually
                        @php
                            if($subsequent_modify_required || $is_subsequent){
                                $calculated_date = \Carbon\Carbon::parse($lease->modifyLeaseApplication->last()->effective_from)->addMonth(6)->format('D M d Y');
                            } else {
                                $accural_date = \Carbon\Carbon::parse($asset->accural_period);
                                $calculated_date = $accural_date->addMonth(6)->subDay(1)->format('D M d Y');
                                //need to check for the current or initial payment basis
                                if($asset->using_lease_payment == '1' && \Carbon\Carbon::parse($asset->accural_period)->lessThan(\Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date))){
                                    //cannot go before the base date...
                                    $calculated_date = \Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)->addMonth(6)->format('D M d Y');
                                }
                            }
                        @endphp
                            _calculated_first_payment_date = new Date("{{ $calculated_date }}");
                        break;
                    case 5:
                        //means selected option is Annually
                        @php
                            if($subsequent_modify_required || $is_subsequent){
                                $calculated_date = \Carbon\Carbon::parse($lease->modifyLeaseApplication->last()->effective_from)->addMonth(12)->format('D M d Y');
                            } else {
                                $accural_date = \Carbon\Carbon::parse($asset->accural_period);
                                $calculated_date = $accural_date->addMonth(12)->subDay(1)->format('D M d Y');
                                //need to check for the current or initial payment basis
                                if($asset->using_lease_payment == '1' && \Carbon\Carbon::parse($asset->accural_period)->lessThan(\Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date))){
                                    //cannot go before the base date...
                                    $calculated_date = \Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)->addMonth(12)->format('D M d Y');
                                }
                            }
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
            $("input[type='checkbox']").on('click', function () {
                var group = "input[name='" + $(this).attr("name") + "']";
                $(group).prop("checked", false);
                $(this).prop("checked", true);
            });

            //calculation (total_amount_per_interval= similar_chateristics_assets*payment_per_interval_per_unit)
            var $total = $('#total_amount_per_interval'),
                $value = $('#payment_per_interval_per_unit');
            $units = $("#similar_chateristics_assets").val();
            $value.on('input', function (e) {
                var total = 0;
                total = $units * parseInt($value.val(), 10);
                // $value.each(function (index, elem) {
                //     if (!Number.isNaN(parseInt(this.value, 10)))
                //         total = $units * parseInt(this.value, 10);
                // });
                $total.val(total);
            });

            var final_payout_dates;

            $('.confirm_lease_payment_due_dates').on('click', function () {
                $start_date = $('#first_payment_start_date').val();
                $end_date = $('#last_payment_end_date').val();
                $payment_interval = $('select[name="payment_interval"]').val();
                $payment_payout = $('select[name="payout_time"]').val();
                $asset_id = '{{ $asset->id }}';
                $lease_id = '{{ $asset->lease->id }}';

                $.ajax({
                    url: '{{ route("lease.payments.duedatesannexure") }}',
                    data: {
                        start_date: $start_date,
                        end_date: $end_date,
                        payment_interval: $payment_interval,
                        payment_payout: $payment_payout,
                        lease_id: $lease_id,
                        asset_id: $asset_id
                    },
                    beforeSend: function () {
                        showOverlayForAjax();
                    },
                    type: 'get',
                    success: function (response) {
                        setTimeout(function () {
                            $('.annexure_modal_body').html(response['html']);
                            $('#overlay').hide();
                            removeOverlayAjax();
                            final_payout_dates = response['final_payout_dates'];
                            @if($subsequent_modify_required || $is_subsequent)
                                var asset_lease_start_date = new Date('{{ \Carbon\Carbon::parse($lease->modifyLeaseApplication->last()->effective_from)->format('Y') ."-". \Carbon\Carbon::parse($lease->modifyLeaseApplication->last()->effective_from)->format('m') }}');
                            @else
                                var asset_lease_start_date = new Date('{{ \Carbon\Carbon::parse($asset->accural_period)->format('Y') ."-". \Carbon\Carbon::parse($asset->accural_period)->format('m') }}');
                            @endif
                            var asset_lease_end_date = new Date('{{ \Carbon\Carbon::parse($asset->getLeaseEndDate($asset))->format('Y')."-".\Carbon\Carbon::parse($asset->getLeaseEndDate($asset))->format('m') }}');
                            //setting up datepicker calendar on each input field.. taking care of lease start date and lease end date as well....

                            $('.alter_due_dates_input').each(function () {
                                var data_year = $(this).data('year');
                                var data_month = $(this).data('month');
                                var date = $(this).val();
                                //var temp_date = new Date(data_year + "-" + data_month);
                                var temp_date = new Date(date);
                                if (temp_date >= asset_lease_start_date && temp_date <= asset_lease_end_date) {
                                    $(this).datepicker({
                                        dateFormat: "yy-mm-dd",
                                        @if($subsequent_modify_required || $is_subsequent)
                                        minDate: new Date('{{ $lease->modifyLeaseApplication->last()->effective_from }}'),
                                        @else
                                        minDate: new Date('{{ $asset->accural_period }}'),
                                        @endif

                                        maxDate: new Date('{{ $asset->getLeaseEndDate($asset) }}'),
                                        stepMonths: 0
                                    });
                                    $(this).datepicker('setDate', temp_date);
                                } else {
                                    $(this).remove();
                                }

                            });

                            $('#myModal').modal('show');

                        }, 100);
                    }
                });
            });

            $("#myModal").on("hidden.bs.modal", function () {
                $(".annexure_modal_body").html("");
            });

            /**
             * Confirm the user before they can override the payment dates
             */
            var is_dates_edited = false;
            $(document.body).on('click', '.edit_payment_due_dates', function () {
                bootbox.confirm({
                    message: "You are over-riding the inputs and any date edit will be considered as final lease payment dates. Are you sure that you want to proceed?",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn-danger'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            //set the dropdowns to Custom here....
                            $('select[name="payment_interval"]').val(6);
                            $('select[name="payout_time"]').val(3);

                            is_dates_edited = true;

                            $('.alter_due_dates_input').toggleClass('hidden');
                            $('.alter_due_dates_info').hide();
                        }
                    }
                });
            });

            $(document.body).on('click', '.add_lease_payment_date', function () {
                var year = $(this).data('year');
                var month = $(this).data('month');
                var temp_date = new Date(year + "-" + month);
                var html = '<input style="width: 100px;" type="text" data-month="' + month + '" data-year="' + year + '" class="form-control alter_due_dates_input" name="altered_payment_due_date[]" value="">';

                $(this).after(html);

                $(this).next('input').datepicker({
                    dateFormat: "yy-mm-dd",
                    @if($subsequent_modify_required || $is_subsequent)
                    minDate: new Date('{{ $lease->modifyLeaseApplication->last()->effective_from }}'),
                    @else
                    minDate: new Date('{{ $asset->accural_period }}'),
                    @endif

                    maxDate: new Date('{{ $asset->getLeaseEndDate($asset) }}'),
                    stepMonths: 0
                });

                $(this).next('input').datepicker('setDate', temp_date);

                $(this).remove();

            });

            /**
             * In case the user have made the changes to the dates than we have to find the first and last payment dates and have to populate them as well...
             */
            var dates_array = [];
            $('#myModal').on('click', '.confirm_payment_due_dates', function () {
                var _first_payment_date;
                var _last_payment_date;
                dates_array = [];

                if (is_dates_edited) {

                    var html = '';

                    $('.alter_due_dates_input').each(function (i, e) {
                        if ($(this).val() != "") {
                            dates_array.push(new Date($(this).val()));
                            html += '<input type="hidden" class="altered_payment_due_date" name="altered_payment_due_date[]" value="' + $(this).val() + '">';
                        }
                    });

                    if (html == "") {
                        bootbox.alert('Payout dates cannot be calculated. Please check your inputs and try again.');
                        return false;
                    }

                    //find the first payment date and the last payment date here and fill the inputs with the values
                    var _last_payment_date = new Date(Math.max.apply(null, dates_array));
                    var _first_payment_date = new Date(Math.min.apply(null, dates_array));

                    $('#first_payment_start_date').datepicker('setDate', _first_payment_date);

                    $('#last_payment_end_date').datepicker('setDate', _last_payment_date);

                    $("#last_lease_payment_interval_end_date").datepicker( "option", "minDate", _last_payment_date);

                    $("#immediate_previous_lease_end_date").datepicker( "option", "maxDate", _first_payment_date);

                } else {
                    //not edited and the user have directly clicked on the edit button

                    var html = '';
                    var final_dates_array = new Array();

                    for (var item in final_payout_dates) {
                        for (var month in final_payout_dates[item]) {
                            for (payout_date in final_payout_dates[item][month]) {
                                final_dates_array.push(new Date(payout_date));
                                html += '<input type="hidden" class="altered_payment_due_date" name="altered_payment_due_date[]" value="' + payout_date + '">';
                            }
                        }
                    }

                    console.log(final_dates_array);
                    //find the first payment date and the last payment date here and fill the inputs with the values
                    var _last_payment_date = new Date(Math.max.apply(null, final_dates_array));

                    var _first_payment_date = new Date(Math.min.apply(null, final_dates_array));

                    $('#first_payment_start_date').datepicker('setDate', _first_payment_date);

                    $('#last_payment_end_date').datepicker('setDate', _last_payment_date);

                    $("#last_lease_payment_interval_end_date").datepicker( "option", "minDate", _last_payment_date);

                    $("#immediate_previous_lease_end_date").datepicker( "option", "maxDate", _first_payment_date);
                }

                if (html != "") {

                    $('input[name="due_dates_confirmed"]').val('1');

                    $('.altered_payment_due_date').remove();

                    $('.altered_payment_due_dates').html(html);

                    $('#myModal').modal('hide');

                    if (dates_array.length > 0) {
                        $(".view_current_edited_dates").show();
                    }

                    setTimeout(function () {
                        bootbox.alert('We have captured your input dates. Please proceed further on the form.');
                    }, 1000);

                } else {
                    bootbox.alert('Unable to capture the payout dates. Please click on edit and provide custom dates.');
                    return false;
                }

            });

            /**
             * Generate the view current payment dates whent the user comes to edit payments
             */
            $('.view_current_lease_payment_due_dates').on('click', function () {
                $.ajax({
                    url: '{{ route('addlease.payments.showpaymentdates', ['id' => $payment->id]) }}',
                    type: 'get',
                    success: function (response) {
                        $('.annexure_modal_body').html(response['html']);
                        $('#myModal').modal('show');
                    }
                });
            });

            /**
             * Generate the view current payment dates whent the user comes to edit payments
             */
            $('.view_current_edited_dates').on('click', function () {
                //need to generate the table view and need to show the table all from javascript...

                var html = "<div class=\"modal-header\">\n" +
                    "    <h5 class=\"modal-title\">Confirm Lease Payment Due Dates</h5>\n" +
                    "    <button type=\"button\" class=\"close\" data-dismiss=\"modal\" aria-label=\"Close\">\n" +
                    "        <span aria-hidden=\"true\">&times;</span>\n" +
                    "    </button>\n" +
                    "</div>\n" +
                    "<div class=\"modal-body\">\n" +
                    "\n" +
                    "        <div class=\"alert alert-info\">\n" +
                    "            <strong>Note!</strong> Below is the list of the payment dates that have been saved previously. If you want to overrider these dates please click on the \"Confirm Lease Payment Due Dates\".\n" +
                    "        </div>\n" +
                    "\n" +
                    "        <div class=\"row\">\n" +
                    "            <div class=\"col-md-12\">\n" +
                    "                <table class=\"table table-bordered\">\n" +
                    "                    <thead>\n" +
                    "                    <tr>\n" +
                    "                        <th>Sr. No.</th>\n" +
                    "                        <th>Payment Due Date</th>\n" +
                    "                    </tr>\n" +
                    "                    </thead>\n" +
                    "                    <tbody>";

                for (i = 0; i < dates_array.length; i++) {
                    var current_index = i + 1;
                    var current_date = dates_array[i];

                    var options = {
                        weekday: "short",
                        year: "numeric",
                        month: "2-digit",
                        day: "numeric"
                    };

                    current_date = current_date.toLocaleDateString("en", options);

                    html += "<tr>\n" +
                        "                            <td>" + current_index + "</td>\n" +
                        "                            <td>\n" +
                        "                                <strong>\n" +
                        "                                    " + current_date + "\n" +
                        "                                </strong>\n" +
                        "                            </td>\n" +
                        "                        </tr>";
                }
                html += "</tbody>\n" +
                    "                </table>\n" +
                    "            </div>\n" +
                    "        </div>\n" +
                    "\n" +
                    "</div>\n" +
                    "<div class=\"modal-footer\">\n" +
                    "   &nbsp;\n" +
                    "</div>";

                $('.annexure_modal_body').html(html);
                $('#myModal').modal('show');

            });

            $('#workings_doc').change(function () {
                $('#workings_doc').show();
                var filename = $('#workings_doc').val();
                var or_name = filename.split("\\");
                $('#upload').val(or_name[or_name.length - 1]);
            });


        });
    </script>
@endsection
