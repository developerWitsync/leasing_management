<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="form-group{{ $errors->has('is_market_value_present') ? ' has-error' : '' }} required">
        <label for="is_escalation_applicable" class="col-md-4 control-label">Is Escalation Applicable</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-check-input" name="is_escalation_applicable" id="yes" type="checkbox" value="yes" @if(old('is_escalation_applicable', $model->is_escalation_applicable) == "yes") checked="checked" @endif>
            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label><br>
            <input class="form-check-input" name="is_escalation_applicable" id="no" type="checkbox" value="no" @if(old('is_escalation_applicable', $model->is_escalation_applicable)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            @if ($errors->has('is_escalation_applicable'))
                <span class="help-block">
                        <strong>{{ $errors->first('is_escalation_applicable') }}</strong>
                    </span>
            @endif
        </div>
    </div>

    <div class="@if(old('is_escalation_applicable', $model->is_escalation_applicable) != 'yes')) hidden @endif hidden_fields">
        <div class="form-group{{ $errors->has('effective_from') ? ' has-error' : '' }} required">
            <label for="effective_from" class="col-md-4 control-label">Escalation Effective From</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" value="{{ old('effective_from', $model->effective_from) }}" class="form-control" id="effective_from" name="effective_from">
                @if ($errors->has('effective_from'))
                    <span class="help-block">
                        <strong>{{ $errors->first('effective_from') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('escalation_basis') ? ' has-error' : '' }} required">
            <label for="effective_from" class="col-md-4 control-label">Escalation Basis</label>
            <div class="col-md-6 form-check form-check-inline">
                <select name="escalation_basis" class="form-control">
                    <option value="">--Select Escalation Basis--</option>
                    @foreach($contract_escalation_basis as $basis)
                        <option value="{{ $basis->id }}" @if(old('escalation_basis', $model->escalation_basis) == $basis->id) selected="selected" @endif>{{ $basis->title }}</option>
                    @endforeach
                </select>
                @if ($errors->has('escalation_basis'))
                    <span class="help-block">
                        <strong>{{ $errors->first('escalation_basis') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('escalation_rate_type') ? ' has-error' : '' }} required escalation_rate_type @if(old('escalation_basis', $model->escalation_basis) != '1') hidden @endif">
            <label for="escalation_rate_type" class="col-md-4 control-label">Escalation Rate Type</label>
            <div class="col-md-6 form-check form-check-inline">
                <select name="escalation_rate_type" class="form-control">
                    <option value="">--Select Rate Type--</option>
                    @foreach($percentage_rate_types as $type)
                        <option value="{{ $type->id }}" @if(old('escalation_rate_type', $model->escalation_rate_type) == $type->id) selected="selected" @endif>{{ $type->title }}</option>
                    @endforeach
                </select>
                @if ($errors->has('escalation_rate_type'))
                    <span class="help-block">
                        <strong>{{ $errors->first('escalation_rate_type') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('is_escalation_applied_annually_consistently') ? ' has-error' : '' }} required">
            <label for="is_escalation_applied_annually_consistently" class="col-md-4 control-label">Escalation Consistently Annually Applied ?</label>
            <div class="col-md-6 form-check form-check-inline" required>
                <input class="form-check-input" name="is_escalation_applied_annually_consistently" id="yes" type="checkbox" value="yes" @if(old('is_escalation_applied_annually_consistently', $model->is_escalation_applied_annually_consistently) == "yes") checked="checked" @endif>
                <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label><br>
                <input class="form-check-input" name="is_escalation_applied_annually_consistently" id="no" type="checkbox" value="no" @if(old('is_escalation_applied_annually_consistently', $model->is_escalation_applied_annually_consistently)  == "no") checked="checked" @endif>
                <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
                @if ($errors->has('is_escalation_applied_annually_consistently'))
                    <span class="help-block">
                        <strong>{{ $errors->first('is_escalation_applied_annually_consistently') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('fixed_rate') ? ' has-error' : '' }} required @if(old('escalation_basis', $model->escalation_basis) == '1' && (old('escalation_rate_type', $model->escalation_rate_type) == '1' || old('escalation_rate_type', $model->escalation_rate_type) == '3'))  @else hidden @endif is_j_12_y_e_s_fixed_rate">
            <label for="fixed_rate" class="col-md-4 control-label">Specify Fixed Rate</label>
            <div class="col-md-6 form-check form-check-inline">
                <select class="form-control" name="fixed_rate">
                    <option value="">--Select Fixed Rate Percentage--</option>
                    @foreach($escalation_percentage_settings as $setting)
                        <option value="{{ $setting->number }}" @if(old('fixed_rate', $model->fixed_rate) == $setting->number) @endif>{{ $setting->number }}%</option>
                    @endforeach
                </select>
                @if ($errors->has('fixed_rate'))
                    <span class="help-block">
                        <strong>{{ $errors->first('fixed_rate') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('current_variable_rate') ? ' has-error' : '' }} required @if(old('escalation_basis', $model->escalation_basis) == '1' && (old('escalation_rate_type', $model->escalation_rate_type) == '2' || old('escalation_rate_type', $model->escalation_rate_type) == '3'))  @else hidden @endif is_j_12_y_e_s_variable_rate">
            <label for="current_variable_rate" class="col-md-4 control-label">Specify the Current Variable Rate</label>
            <div class="col-md-6 form-check form-check-inline">
                <select class="form-control" name="current_variable_rate">
                    <option value="">--Select Current Variable Rate--</option>
                    @foreach($escalation_percentage_settings as $setting)
                        <option value="{{ $setting->number }}" @if(old('current_variable_rate', (int)$model->current_variable_rate) == $setting->number) selected="selected" @endif>{{ $setting->number }}%</option>
                    @endforeach
                </select>
                @if ($errors->has('current_variable_rate'))
                    <span class="help-block">
                        <strong>{{ $errors->first('current_variable_rate') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('total_escalation_rate') ? ' has-error' : '' }} required total_escalation_rate @if(old('escalation_basis', $model->escalation_basis) != '1') hidden @endif">
            <label for="total_escalation_rate" class="col-md-4 control-label">Total Escalation Rate</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" class="form-control" placeholder="Total Escalation Rate" name="total_escalation_rate" value="{{ old('total_escalation_rate', $model->total_escalation_rate) }}">
                @if ($errors->has('total_escalation_rate'))
                    <span class="help-block">
                        <strong>{{ $errors->first('total_escalation_rate') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('amount_based_currency') ? ' has-error' : '' }} required @if(old('escalation_basis', $model->escalation_basis) == '1' || old('is_escalation_applied_annually_consistently', $model->is_escalation_applied_annually_consistently) == 'no') hidden @endif amount_based_fields">
            <label for="amount_based_currency" class="col-md-4 control-label">Currency</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" class="form-control" placeholder="Currency" name="amount_based_currency" value="{{ $lease->lease_contract_id }}" readonly="readonly">
                @if ($errors->has('amount_based_currency'))
                    <span class="help-block">
                        <strong>{{ $errors->first('amount_based_currency') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('escalated_amount') ? ' has-error' : '' }} required @if(old('escalation_basis', $model->escalation_basis) == '1' || old('is_escalation_applied_annually_consistently', $model->is_escalation_applied_annually_consistently) == 'no') hidden @endif amount_based_escalation_amount">
            <label for="escalated_amount" class="col-md-4 control-label">Enter Amount of Increase</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" class="form-control" placeholder="Enter Amount of Increase" name="escalated_amount" value="{{ old('escalated_amount', $model->escalated_amount) }}" >
                @if ($errors->has('escalated_amount'))
                    <span class="help-block">
                        <strong>{{ $errors->first('escalated_amount') }}</strong>
                    </span>
                @endif
            </div>
        </div>

    </div>

    <!-- Inconsistently Applied Form fields -->

    <div class="form-group inconsistently_applied @if(old('is_escalation_applied_annually_consistently', $model->is_escalation_applied_annually_consistently) == 'yes') hidden @endif">
        <table class="table table-bordered table-condensed">
            <thead>
            <th>Relevant Years</th>
            <th>Escalation Frequency</th>
            </thead>
            <tbody>
            @foreach($years as $year)
                <tr>
                    <td>{{ $year }}</td>
                    <td>
                        <select class="form-control escalation_frequency" name="inconsistent_escalation_frequency[{{$year}}][]" data-year="{{$year}}">
                            <option value="">--Select Escalation Frequency--</option>
                            @foreach($escalation_frequency as $frequency)
                                <option value="{{ $frequency->frequency }}">{{ $frequency->title }}</option>
                            @endforeach
                        </select>

                        <table width="100%" cellpadding="0" cellspacing="0" border="0" class="escalation_inconsistent_table escalation_inconsistent_table_{{$year}} table table-condensed table-bordered hidden">
                            <thead class="theads_escalations">
                            </thead>
                            <tbody class="replace_with_{{$year}}">

                            </tbody>
                        </table>

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    <!-- Inconsistently Applied Form fields ends here -->


    <!-- will be visible when wither the "amount_based_escalation_amount" or "total_escalation_rate" is visible -->

    <div class="form-group see_escalation_chart @if(old('escalation_basis', $model->escalation_basis) != '' && old('is_escalation_applicable', $model->is_escalation_applicable) == 'yes') @else hidden @endif hidden_fields">
        <div class="col-md-6 col-md-offset-4">
            <a href="javascript:void(0);" class="btn btn-info compute_escalation">Compute</a>
        </div>
    </div>

    <div class="form-group{{ $errors->has('escalation_currency') ? ' has-error' : '' }} required @if(old('escalation_basis', $model->escalation_basis) != '' && old('is_escalation_applicable', $model->is_escalation_applicable) == 'yes') @else hidden @endif computed_fields hidden_fields">
        <label for="amount_based_currency" class="col-md-4 control-label">Currency</label>
        <div class="col-md-6 form-check form-check-inline">
            <input type="text" class="form-control" placeholder="Escalation Currency" name="escalation_currency" value="{{ $lease->lease_contract_id }}" readonly="readonly">
            @if ($errors->has('escalation_currency'))
                <span class="help-block">
                        <strong>{{ $errors->first('escalation_currency') }}</strong>
                    </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('total_undiscounted_lease_payment_amount') ? ' has-error' : '' }} required @if(old('escalation_basis', $model->escalation_basis) != '' && old('is_escalation_applicable', $model->is_escalation_applicable) == 'yes') @else hidden @endif computed_fields hidden_fields">
        <label for="escalated_amount" class="col-md-4 control-label">Total Undiscounted Lease Payments</label>
        <div class="col-md-6 form-check form-check-inline">
            <input type="text" class="form-control" placeholder="Total Undiscounted Lease Payments" name="total_undiscounted_lease_payment_amount" value="{{ old('total_undiscounted_lease_payment_amount', $model->total_undiscounted_lease_payment_amount) }}" id="computed_total" readonly="readonly">
            @if ($errors->has('total_undiscounted_lease_payment_amount'))
                <span class="help-block">
                        <strong>{{ $errors->first('total_undiscounted_lease_payment_amount') }}</strong>
                    </span>
            @endif
        </div>
    </div>

    <div class="form-group see_escalation_chart @if(old('escalation_basis', $model->escalation_basis) != '' && old('is_escalation_applicable', $model->is_escalation_applicable) == 'yes') @else hidden @endif hidden_fields">
        <div class="col-md-6 col-md-offset-4">
            <a href="javascript:void(0);" class="btn btn-info show_escalation_chart">See Escalation Chart</a>
        </div>
    </div>



    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">

            <a href="" class="btn btn-danger">Cancel</a>
            <button type="submit" class="btn btn-success">
                Submit
            </button>
        </div>
    </div>

</form>


<div style="display: none" class="inconsistent_clonable_row">

</div>

<!--Escalations Chart -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="dialog">
        <div class="modal-content escalation_chart_modal_body">

        </div>
    </div>
</div>

<!--Escalations Chart -->
@section('footer-script')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/pages/escalations.js') }}"></script>
    <script>

       var paymentDueDates = '{!! json_encode($paymentDueDates)  !!}';

       var _global_fixed_rate_select = '<select class="form-control" name="inconsistent_fixed_rate[YEAR][]" data-year="YEAR" onChange="javascript:calculateTotalEscalationRateInconsistent(this)">\n' +
           '                    <option value="">--Select Fixed Rate Percentage--</option>\n' +
           '                    @foreach($escalation_percentage_settings as $setting)\n' +
           '                        <option value="{{ $setting->number }}">{{ $setting->number }}%</option>\n' +
           '                    @endforeach\n' +
           '                </select>';

       var _global_current_variable_rate_select = '<select class="form-control" name="inconsistent_current_variable_rate[YEAR][]" data-year="YEAR" onChange="javascript:calculateTotalEscalationRateInconsistent(this)">\n' +
           '                    <option value="">--Select Current Variable Rate--</option>\n' +
           '                    @foreach($escalation_percentage_settings as $setting)\n' +
           '                        <option value="{{ $setting->number }}">{{ $setting->number }}%</option>\n' +
           '                    @endforeach\n' +
           '</select>';

       var lease_contract_id = "{{ $lease->lease_contract_id }}";

       var _show_escalation_char_url = "{{ route('lease.escalation.showescalationchart', ['id' => $payment->id]) }}";

       var _compute_escalation_url = "{{ route('lease.escalation.compute', ['id' => $payment->id]) }}";

       var _inconsistent_escalation_inputs = '{!! json_encode(unserialize($inconsistentDataModel->inconsistent_data)) !!}';

       var effective_date_calendar_options = {
           dateFormat: "dd-M-yy",
           maxDate : new Date('{{ $lease_end_date }}'),
           @if($payment->using_lease_payment == '1')
           //1 => Current Lease Payment as on Jan 01, 2019
           yearRange : '2019:{{ \Carbon\Carbon::parse($lease_end_date)->format('Y') }}'
           @else
           //2=> Initial Lease Payment as on First Lease Start
           minDate : new Date('{{ $payment->asset->accural_period }}')
           @endif
       };

       $("#effective_from").datepicker(effective_date_calendar_options);

       /**
        * calculate total escalation rate when the inconsistent escalations needs to be applied
        * @param that
        */
       function calculateTotalEscalationRateInconsistent(that){
           var year = $(that).data('year');
           var current_variable_rate = ($('select[name="inconsistent_current_variable_rate['+year+'][]"]').length) ? $('select[name="inconsistent_current_variable_rate['+year+'][]"]').val() : 0;
           var fixed_rate = ($('select[name="inconsistent_fixed_rate['+year+'][]"]').length ) ? $('select[name="inconsistent_fixed_rate['+year+'][]"]').val() : 0;
           var total = parseInt(((current_variable_rate!="")?current_variable_rate:0)) + parseInt(((fixed_rate !="")?fixed_rate:0));
           $('input[name="inconsistent_total_escalation_rate['+year+'][]"]').val(total);
       }

    </script>
@endsection