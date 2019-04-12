<form role="form" class="form-horizontal"
      action="{{ route('addlease.leaseterminationoption.index', ['id' => $lease->id]) }}" id="lease_termination"
      method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="categoriesOuter clearfix">
    <!--     <div class="form-group required">
        <label for="uuid" class="col-md-12 control-label">ULA Code</label>
        <div class="col-md-12 form-check form-check-inline">
            <input type="text" value="{{ $asset->uuid}}" class="form-control" id="uuid" name="uuid" disabled="disabled">
        </div>
    </div> -->

        <div class="form-group required">
            <label for="asset_name" class="col-md-12 control-label">Lease Asset Name</label>
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

        <div class="form-group{{ $errors->has('lease_termination_option_available') ? ' has-error' : '' }} required">
            <label for="name" class="col-md-12 control-label">Lease Termination Option Available Under the
                Contract</label>
            <div class="col-md-12 form-check form-check-inline mrktavail" required>
            <span><input class="form-check-input" name="lease_termination_option_available" id="yes" type="checkbox"
                         value="yes"
                         @if(old('lease_termination_option_available', $model->lease_termination_option_available) == "yes") checked="checked" @endif>
            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label>
            </span>
                <span>
            <input class="form-check-input" name="lease_termination_option_available" id="no" type="checkbox" value="no"
                   @if(old('lease_termination_option_available', $model->lease_termination_option_available)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            </span>
                @if ($errors->has('lease_termination_option_available'))
                    <span class="help-block">
                    <strong>{{ $errors->first('lease_termination_option_available') }}</strong>
                </span>
                @endif
            </div>
        </div>

        <div class="hidden-group" id="hidden-field"
             @if(old('lease_termination_option_available', $model->lease_termination_option_available) == "yes") style="display:block;"
             @else  style="display:none;" @endif>
            <div class="form-group{{ $errors->has('exercise_termination_option_available') ? ' has-error' : '' }} required">
                <label for="name" class="col-md-12 control-label">Reasonable Certainity to Exercise Termination Option
                    as of
                    today</label>
                <div class="col-md-12 form-check form-check-inline mrktavail" required>
                <span><input class="form-check-input" name="exercise_termination_option_available" id="yes"
                             type="checkbox"
                             value="yes"
                             @if(old('exercise_termination_option_available', $model->exercise_termination_option_available) == "yes") checked="checked" @endif>
                <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label>
                </span>
                    <span>
                    <input class="form-check-input" name="exercise_termination_option_available" id="no" type="checkbox"
                           value="no"
                           @if(old('exercise_termination_option_available', $model->exercise_termination_option_available)  == "no") checked="checked" @endif>
                    <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
                </span>
                    @if ($errors->has('exercise_termination_option_available'))
                        <span class="help-block">
                        <strong>{{ $errors->first('exercise_termination_option_available') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>

        <div class="hidden-group" id="hidden-elements"
             @if(old('exercise_termination_option_available',$model->exercise_termination_option_available) == "yes" && old('lease_termination_option_available', $model->lease_termination_option_available) == "yes") style="display:block;"
             @else  style="display:none;" @endif>
            <div class="form-group{{ $errors->has('lease_end_date') ? ' has-error' : '' }} required">
                <label for="lease_end_date" class="col-md-12 control-label">Expected Lease End Date</label>
                <div class="col-md-12">

                    <input type="text" placeholder="Expected Lease End Date" class="form-control lease_period"
                           id="lease_end_date"
                           name="lease_end_date" value="{{ old('lease_end_date', $model->lease_end_date) }}"
                           autocomplete="off" readonly="readonly" style="background-color:#fff">

                    @if ($errors->has('lease_end_date'))
                        <span class="help-block">
                        <strong>{{ $errors->first('lease_end_date') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('termination_penalty_applicable') ? ' has-error' : '' }} required">
                <label for="name" class="col-md-12 control-label">Termination Penalty Applicable</label>
                <div class="col-md-12 form-check form-check-inline mrktavail" required>
                <span><input class="form-check-input" name="termination_penalty_applicable" id="yes" type="checkbox"
                             value="yes"
                             @if(old('termination_penalty_applicable', $model->termination_penalty_applicable) == "yes") checked="checked" @endif>
                <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label>
                </span>
                    <span><input class="form-check-input" name="termination_penalty_applicable" id="no" type="checkbox"
                                 value="no"
                                 @if(old('termination_penalty_applicable', $model->termination_penalty_applicable)  == "no") checked="checked" @endif>
                <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
                </span>
                    @if ($errors->has('termination_penalty_applicable'))
                        <span class="help-block">
                        <strong>{{ $errors->first('termination_penalty_applicable') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
        <div class="hidden-group" id="hidden-fields"
             @if(old('termination_penalty_applicable',$model->termination_penalty_applicable) == "yes" && old('exercise_termination_option_available',$model->exercise_termination_option_available ) == "yes" && old('lease_termination_option_available', $model->lease_termination_option_available) == "yes") style="display:block;"
             @else  style="display:none;" @endif>
            <div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }} required">
                <label for="currency" class="col-md-12 control-label">Currency</label>
                <div class="col-md-12 form-check form-check-inline">
                    <input type="text" value="{{ $lease->lease_contract_id }}" class="form-control" id="currency"
                           name="currency" readonly="readonly">
                    @if ($errors->has('currency'))
                        <span class="help-block">
                        <strong>{{ $errors->first('currency') }}</strong>
                    </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('termination_penalty') ? ' has-error' : '' }} required">
                <label for="termination_penalty" class="col-md-12 control-label">Termination Penalty</label>
                <div class="col-md-12 form-check form-check-inline">
                    <input type="text" class="form-control" id="termination_penalty" name="termination_penalty"
                           value="{{ old('termination_penalty', $model->termination_penalty) }}">
                    @if ($errors->has('termination_penalty'))
                        <span class="help-block">
                        <strong>{{ $errors->first('termination_penalty') }}</strong>
                    </span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <div class="form-group btnMainBx">
        <div class="col-md-4 col-sm-4 btn-backnextBx">
            <a href="{{ route('addlease.leaseasset.index', ['id' => $lease->id]) }}" class="btn btn-danger"><i
                        class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
        </div>
        <div class="col-md-4 col-sm-4 btnsubmitBx aligncenter">
            <button type="submit" class="btn btn-success">
                {{ env('SAVE_LABEL') }} <i class="fa fa-download"></i></button>
        </div>
        <div class="col-md-4 col-sm-4 btn-backnextBx rightlign ">
            <input type="hidden" name="action" value="">
            <a href="javascript:void(0);" class="btn btn-primary save_next"> {{ env('NEXT_LABEL') }} <i
                        class="fa fa-arrow-right"></i></a>
        </div>
    </div>
</form>

@section('footer-script')
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script type="text/javascript">

        $('.save_next').on('click', function (e) {

            e.preventDefault();
            $('input[name="action"]').val('next');
            $('#lease_termination').submit();
        });

        $(document).ready(function () {
            @if($subsequent_modify_required)
                var minDate = new Date('{{ $asset->lease->modifyLeaseApplication->last()->effective_from}}');
            @else
                @if(\Carbon\Carbon::parse($asset->accural_period)->greaterThan(\Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)))
                    var minDate = new Date('{{ $asset->accural_period }}');
                @else
                    var minDate = new Date('{{ getParentDetails()->accountingStandard->base_date }}');
                @endif
            @endif

            $("#lease_end_date").datepicker({
                dateFormat: "dd-M-yy",
                minDate: minDate,
                changeMonth: true,
                changeYear: true,
                maxDate: new Date('{{ $asset->lease_end_date }}'),
                {!!  getYearRanage() !!}
                onSelect: function (date, instance) {
                    var _ajax_url = '{{route("lease.checklockperioddate")}}';
                    checklockperioddate(date, instance, _ajax_url);
                }
            });
        });

        $(document).on('click', 'input[name="lease_termination_option_available"]', function () {
            $('input[name="lease_termination_option_available"]').not(this).prop('checked', false);
            if ($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-field').show();

            } else {

                //make all the other fields value as blank

                $('input[name="exercise_termination_option_available"]').prop('checked', false);
                $('#lease_end_date').val('');
                $('input[name="termination_penalty_applicable"]').prop('checked', false);
                $('#termination_penalty').val('');


                $('#hidden-field').hide();
                $('#hidden-fields').hide();
                $('#hidden-elements').hide();
            }
        });
        $(document).on('click', 'input[name="exercise_termination_option_available"]', function () {
            $('input[name="exercise_termination_option_available"]').not(this).prop('checked', false);
            if ($(this).is(':checked') && $(this).val() == 'yes') {
                //show pop up here
                bootbox.dialog({
                    message: "Please input, verify and update your Lease Payments Schedule as well under step 6 to restrict Lease Payments up to the date of Lease Termination. Failure to do so may impact the Lease Valuation.",
                    buttons: {
                        confirm: {
                            label: 'Ok',
                            className: 'btn-success'
                        },
                    }
                });

                $('#hidden-elements').show();
            } else {

                $('#lease_end_date').val('');
                $('input[name="termination_penalty_applicable"]').prop('checked', false);
                $('#termination_penalty').val('');

                $('#hidden-elements').hide();
                $('#hidden-fields').hide();
            }
        });
        $(document).on('click', 'input[name="termination_penalty_applicable"]', function () {
            $('input[name="termination_penalty_applicable"]').not(this).prop('checked', false);
            if ($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-fields').show();
            } else {
                $('#hidden-fields').hide();

                $('#termination_penalty').val('');

                //$('#hidden-elements').hide();
                $('#hidden-fields').hide();
            }
        });

    </script>
@endsection