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

    <div class="hidden hidden_fields">
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
                    <option>--Select Escalation Basis--</option>
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

        <div class="form-group{{ $errors->has('escalation_rate_type') ? ' has-error' : '' }} required escalation_rate_type hidden">
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

        <div class="form-group{{ $errors->has('applied_annually') ? ' has-error' : '' }} required">
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

        <div class="form-group{{ $errors->has('fixed_rate') ? ' has-error' : '' }} required hidden is_j_12_y_e_s_fixed_rate">
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

        <div class="form-group{{ $errors->has('current_variable_rate') ? ' has-error' : '' }} required hidden is_j_12_y_e_s_variable_rate">
            <label for="current_variable_rate" class="col-md-4 control-label">Specify the Current Variable Rate</label>
            <div class="col-md-6 form-check form-check-inline">
                <select class="form-control" name="current_variable_rate">
                    <option value="">--Select Current Variable Rate--</option>
                    @foreach($escalation_percentage_settings as $setting)
                        <option value="{{ $setting->number }}" @if(old('current_variable_rate', $model->current_variable_rate) == $setting->number) @endif>{{ $setting->number }}%</option>
                    @endforeach
                </select>
                @if ($errors->has('current_variable_rate'))
                    <span class="help-block">
                        <strong>{{ $errors->first('current_variable_rate') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('total_escalation_rate') ? ' has-error' : '' }} required total_escalation_rate hidden">
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



    </div>

    <div class="form-group total_escalation_rate hidden">
        <div class="col-md-6 col-md-offset-4">
            <a href="javascript:void(0);" class="btn btn-info show_escalation_chart">See Escalation Chart</a>
        </div>
    </div>

</form>

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
    <script>

        $('.show_escalation_chart').on('click', function(){
            $.ajax({
                url : "{{ route('lease.escalation.showescalationchart', ['id' => $payment->id]) }}",
                data : $('form').serialize(),
                type : 'get',
                success : function(response){
                    setTimeout(function () {
                        $('.escalation_chart_modal_body').html(response);

                        $('#myModal').modal('show');
                    }, 100);
                }
            });
        });

        $(document).on('click', 'input[type="checkbox"][name="is_escalation_applicable"]', function() {
            $('input[type="checkbox"][name="is_escalation_applicable"]').not(this).prop('checked', false);

            if($(this).is(':checked') && $(this).val() == 'yes') {
                $('.hidden_fields').removeClass('hidden');
            } else {
                $('.hidden_fields').addClass('hidden');
            }
        });

        $(document).ready(function () {
            $("#effective_from").datepicker({
                dateFormat: "dd-M-yy",
                maxDate : new Date('{{ $lease_end_date }}'),
                @if($payment->using_lease_payment == '1')
                    //1 => Current Lease Payment as on Jan 01, 2019
                    minYear : '2019'
                @else
                    //2=> Initial Lease Payment as on First Lease Start
                    minDate : new Date('{{ $payment->asset->accural_period }}')
                @endif
            });

            //toggle Rate Type Dropdown on the basis of the Escalation Basis selected
            $('select[name="escalation_basis"]').on('change', function () {
                if($(this).val() == '1') {
                    //Rate Based
                    $('.escalation_rate_type').removeClass('hidden');
                } else {
                    //amount based
                    $('.is_j_12_y_e_s_fixed_rate').addClass('hidden');
                    $('.is_j_12_y_e_s_variable_rate').addClass('hidden');

                    $('select[name="fixed_rate"]').val('');
                    $('select[name="current_variable_rate"]').val('');

                    $('.total_escalation_rate').addClass('hidden');
                    $('input[name="total_escalation_rate"]').val('');
                    $('.escalation_rate_type').addClass('hidden');

                    $('select[name="escalation_rate_type"]').val('');
                }
            });

            //show the pop up and confirm messages on the Escalation Consistently Annually Applied Post Effective Date checkbox
            $(document).on('click', 'input[type="checkbox"][name="is_escalation_applied_annually_consistently"]', function() {
                $('input[type="checkbox"][name="is_escalation_applied_annually_consistently"]').not(this).prop('checked', false);
                var that = $(this);
                if($(this).is(':checked') && $(this).val() == 'yes') {
                    bootbox.confirm({
                        message: "Every Year Escalation will be applied at the same rate on Previous Year Lease Payment. Are you sure?",
                        buttons: {
                            confirm: {
                                label: 'Yes' ,
                                className: 'btn-success'
                            },
                            cancel: {
                                label: 'No',
                                className: 'btn-danger'
                            }
                        },
                        callback: function (result) {
                            if(!result) {
                                confirmWhenEscalationAppliedInconsistently();
                            } else {
                                //check if the fixed/fixed & variable selected in j11
                                if($('select[name="escalation_rate_type"]').val() == '1' || $('select[name="escalation_rate_type"]').val() == '3') {
                                    $('.is_j_12_y_e_s_fixed_rate').removeClass('hidden');
                                    $('.total_escalation_rate').removeClass('hidden');
                                }

                                if($('select[name="escalation_rate_type"]').val() == '2' || $('select[name="escalation_rate_type"]').val() == '3') {
                                    $('.is_j_12_y_e_s_variable_rate').removeClass('hidden');
                                    $('.total_escalation_rate').removeClass('hidden');
                                }
                            }
                        }
                    });
                } else if($(this).is(':checked') && $(this).val() == 'no'){
                    confirmWhenEscalationAppliedInconsistently();
                } else {
                    $('.is_j_12_y_e_s_fixed_rate').addClass('hidden');
                    $('.is_j_12_y_e_s_variable_rate').addClass('hidden');

                    $('select[name="current_fixed_rate"]').val('');
                    $('select[name="current_variable_rate"]').val('');

                    $('.total_escalation_rate').addClass('hidden');
                    $('input[name="total_escalation_rate"]').val('');

                }
            });

            /**
             * confirmation pop up when no is selected for escalations applied inconsistently.
             */
            function confirmWhenEscalationAppliedInconsistently(){
                //need to show other confirm box here
                bootbox.confirm({
                    message: "Are You Sure that the Escalation applied inconsistently?",
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
                        $('.is_j_12_y_e_s_fixed_rate').addClass('hidden');
                        $('.is_j_12_y_e_s_variable_rate').addClass('hidden');

                        $('select[name="fixed_rate"]').val('');
                        $('select[name="current_variable_rate"]').val('');

                        $('.total_escalation_rate').addClass('hidden');
                        $('input[name="total_escalation_rate"]').val('');

                        if(result) {
                            $('input[type="checkbox"][name="is_escalation_applied_annually_consistently"][value="yes"]').prop('checked', false);
                            $('input[type="checkbox"][name="is_escalation_applied_annually_consistently"][value="no"]').prop('checked', true);
                        } else {
                            $('input[type="checkbox"][name="is_escalation_applied_annually_consistently"]').prop('checked', false);
                        }
                    }
                });
            }

            $('select[name="escalation_rate_type"]').on('change', function(){
                var checkbox_value = $('input[type="checkbox"][name="is_escalation_applied_annually_consistently"]:checked').val();

                //If YES Selected in J12.1 and Fixed rate / Fixed & Variable in J11
                if(($(this).val() == '1' || $(this).val() == '3') && typeof (checkbox_value)!= "undefined" && checkbox_value == 'yes'){
                    $('.is_j_12_y_e_s_fixed_rate').removeClass('hidden');
                } else {
                    $('select[name="fixed_rate"]').val('');
                    $('.is_j_12_y_e_s_fixed_rate').addClass('hidden');
                }

                //If YES Selected in J12.1 and Variable rate / Fixed & Variable in J11
                if(($(this).val() == '2' || $(this).val() == '3') && typeof (checkbox_value)!= "undefined" && checkbox_value == 'yes'){
                    $('select[name="fixed_rate"]').val('');
                    $('.is_j_12_y_e_s_variable_rate').removeClass('hidden');
                } else {
                    $('select[name="current_variable_rate"]').val('');
                    $('.is_j_12_y_e_s_variable_rate').addClass('hidden');
                }

                if($(this).val() != '' && checkbox_value == "yes"){
                    $('.total_escalation_rate').removeClass('hidden');
                    calculateTotalEscalationRate();
                } else {
                    $('.total_escalation_rate').addClass('hidden');
                }
            });

            //calculate total escalation rate based upon the fixed and variable rates
            $('select[name="current_variable_rate"] , select[name="fixed_rate"]').on('change', function(){
                calculateTotalEscalationRate();
            });

            /**
             * calculate the Total escalation rate when the percentage rate is selected and when the Yes is selected
             */
            function calculateTotalEscalationRate(){
                var current_variable_rate = $('select[name="current_variable_rate"]').val();
                var fixed_rate = $('select[name="fixed_rate"]').val();
                var total = parseInt(((current_variable_rate!="")?current_variable_rate:0)) + parseInt(((fixed_rate !="")?fixed_rate:0));
                $('input[name="total_escalation_rate"]').val(total);
            }
        });
    </script>
@endsection