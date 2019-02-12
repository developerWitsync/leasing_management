<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data" id="lease_renewable" action="{{ route('addlease.renewable.index', ['id' => $lease->id]) }}">
    {{ csrf_field() }}
    <div class="categoriesOuter clearfix">
    <div class="form-group required">
        <label for="uuid" class="col-md-12 control-label">ULA Code</label>
        <div class="col-md-12 form-check form-check-inline">
            <input type="text" value="{{ $asset->uuid}}" class="form-control" id="uuid" name="uuid" disabled="disabled">
        </div>
    </div>

    <div class="form-group required">
        <label for="asset_name" class="col-md-12 control-label">Asset Name</label>
        <div class="col-md-12 form-check form-check-inline">
            <input type="text" value="{{ $asset->name}}" class="form-control" id="asset_name" name="asset_name"
                   disabled="disabled">
        </div>
    </div>

    <div class="form-group required">
        <label for="asset_category" class="col-md-12 control-label">Lease Asset Classification</label>
        <div class="col-md-12 form-check form-check-inline">
            <input type="text" value="{{ $asset->category->title}}" class="form-control" id="asset_category"
                   name="asset_category" disabled="disabled">
        </div>
    </div>

    <div class="form-group{{ $errors->has('is_renewal_option_under_contract') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-12 control-label">Lease Payment</label>
        <div class="col-md-12 form-check form-check-inline mrktavail" required>
            <span><input class="form-check-input" name="is_renewal_option_under_contract" id="yes" type="checkbox" value="yes"
                   @if(old('is_renewal_option_under_contract', $model->is_renewal_option_under_contract) == "yes") checked="checked" @endif>
            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label>
            </span>
            <span><input class="form-check-input" name="is_renewal_option_under_contract" id="no" type="checkbox" value="no"
                   @if(old('is_renewal_option_under_contract', $model->is_renewal_option_under_contract)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            </span>
            @if ($errors->has('is_renewal_option_under_contract'))
                <span class="help-block">
                    <strong>{{ $errors->first('is_renewal_option_under_contract') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('renewal_option_not_available_reason') ? ' has-error' : '' }} required renewal_option_not_available_reason"
         @if(old('is_renewal_option_under_contract', $model->is_renewal_option_under_contract) == 'no') style="display: block;"
         @else style="display: none" @endif>
        <label for="renewal_option_not_available_reason" class="col-md-12 control-label">Reason for Renewal Option not
            Present</label>
        <div class="col-md-12">
            <input type="text" id="renewal_option_not_available_reason" placeholder="Reasons"
                   name="renewal_option_not_available_reason" class="form-control"
                   value="{{ old('renewal_option_not_available_reason', $model->renewal_option_not_available_reason) }}">
            @if ($errors->has('renewal_option_not_available_reason'))
                <span class="help-block">
                    <strong>{{ $errors->first('renewal_option_not_available_reason') }}</strong>
                </span>
            @endif
        </div>
    </div>

    <div class="hidden-group gg {{$model->is_renewal_option_under_contract}}" id="hidden-fields"
         @if(old('is_renewal_option_under_contract',$model->is_renewal_option_under_contract ) == "yes") style="display:block;"
         @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('is_reasonable_certainity_option') ? ' has-error' : '' }} required">
            <label for="type" class="col-md-12 control-label">Reasonable Certainity to Exercise Renewal Option as of
                today</label>
            <div class="col-md-12 mrktavail">
                <span>
                        <input class="form-check-input" name="is_reasonable_certainity_option" type="checkbox"
                       id="is_reasonable_certainity_option_yes" value="yes"
                       @if(old('is_reasonable_certainity_option', $model->is_reasonable_certainity_option)  == "yes") checked="checked" @endif >
                        <label clas="form-check-label" for="is_reasonable_certainity_option_yes"
                       style="vertical-align: 4px">Yes</label>
                </span>
                <span>
                        <input class="form-check-input" name="is_reasonable_certainity_option" type="checkbox"
                       id="is_reasonable_certainity_option_no" value="no"
                       @if(old('is_reasonable_certainity_option', $model->is_reasonable_certainity_option)  == "no") checked="checked" @endif>
                        <label class="form-check-label" for="is_reasonable_certainity_option_no"
                       style="vertical-align: 4px">No</label>
                </span>
                @if ($errors->has('is_reasonable_certainity_option'))
                    <span class="help-block">
                        <strong>{{ $errors->first('is_reasonable_certainity_option') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

    <div class="hidden-group gg" id="hidden-fields_date"
         @if(old('is_reasonable_certainity_option',$model->is_reasonable_certainity_option ) == "yes" && old('is_renewal_option_under_contract', $model->is_renewal_option_under_contract) == 'yes') style="display:block;"
         @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('expected_lease_end_Date') ? ' has-error' : '' }} required">
            <label for="expected_lease_end_Date" class="col-md-12 control-label">Expected Lease End Date</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" class="form-control" placeholder="Expected Lease End Date"
                       id="expected_lease_end_Date" name="expected_lease_end_Date"
                       value="{{old('expected_lease_end_Date', $model->expected_lease_end_Date)}}">
                @if ($errors->has('expected_lease_end_Date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('expected_lease_end_Date') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>

</div>
<div class="form-group btnMainBx">

    <div class="col-md-4 col-sm-4 btn-backnextBx">
        <a href="{{ route('addlease.leaseterminationoption.index', ['id' => $lease->id]) }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
    </div>
    <div class="col-md-4 col-sm-4 btnsubmitBx aligncenter">
        <button type="submit" class="btn btn-success"> 
        {{ env('SAVE_LABEL') }} <i class="fa fa-download"></i></button>
    </div>
    <div class="col-md-4 col-sm-4 btn-backnextBx rightlign ">
        <input type="hidden" name="action" value="">
        <a href="javascript:void(0);" class="btn btn-primary save_next"> {{ env('NEXT_LABEL') }} <i class="fa fa-arrow-right"></i></a>
    </div>
 
</div>

</form>

@section('footer-script')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script type="text/javascript">
        $(document).on('click', 'input[name="is_renewal_option_under_contract"]', function () {
            $('input[name="is_renewal_option_under_contract"]').not(this).prop('checked', false);

            if ($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-fields').show();
                $('#renewal_option_not_available_reason').val('');
                $('.renewal_option_not_available_reason').hide();
            } else if ($(this).is(':checked') && $(this).val() == 'no') {
                $('#hidden-fields').hide();
                $('#hidden-fields_date').hide();
                $('input[name="is_reasonable_certainity_option"]').prop('checked', false);
                $('input[name="is_reasonable_certainity_option"][value="no"]').prop('checked', true);
                $('#expected_lease_end_Date').val('');
                bootbox.confirm({
                    message: "Are you sure that the extension option is not available under the contract?",
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
                            //ask for the reason for the no and save to the database as well...
                            $('.renewal_option_not_available_reason').show();
                        } else {
                            $('.renewal_option_not_available_reason').hide();
                            $('input[name="is_renewal_option_under_contract"]').prop('checked', false);
                        }
                    }
                });

            }
        });

        $(document).on('click', 'input[name="is_reasonable_certainity_option"]', function () {
            $('input[name="is_reasonable_certainity_option"]').not(this).prop('checked', false);

            if ($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-fields_date').show();
                $("#is_reasonable_certainity_option_no").prop('checked', false);
            } else {
                $('#hidden-fields_date').hide();
                $("#is_reasonable_certainity_option_yes").prop('checked', false);
            }


        });

        var date = new Date('{{ $asset->lease_end_date }}');
        // add a day
        date.setDate(date.getDate() + 1);
        $('#expected_lease_end_Date').datepicker({
            dateFormat: "dd-M-yy",
            startDate: date,
            minDate: date,
        });

        $("#confirm").on("click", function (e) {
            e.preventDefault();
            var modal = bootbox.dialog({
                message: "The lease payments till the end of the renewable period will be considered for valuation. If you would like to not consider renewal period under lease valuation, please select no to exercise renewal option",
                buttons: [
                    {
                        label: "Ok",
                        className: "btn btn-success pull-left",
                        callback: function () {
                        }
                    },
                ],
                show: false,
                onEscape: function () {
                    modal.modal("hide");
                }
            });
            modal.modal("show");
        });

        $('.save_next').on('click', function (e) {
           
                e.preventDefault();
                $('input[name="action"]').val('next');
                $('#lease_renewable').submit();
        });
    </script>

@endsection