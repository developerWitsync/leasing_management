<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data" id="lease_purchase">
    {{ csrf_field() }}
    <div class="categoriesOuter clearfix">
   
    <div class="form-group required">
        <label for="asset_name" class="col-md-12 control-label">Asset Name</label>
        <div class="col-md-12 form-check form-check-inline">
            <input type="text" value="{{ $asset->name}}" class="form-control" id="asset_name" name="asset_name" disabled="disabled">
        </div>
    </div>

    <div class="form-group required">
        <label for="asset_category" class="col-md-12 control-label">Lease Asset Classification</label>
        <div class="col-md-12 form-check form-check-inline">
            <input type="text" value="{{ $asset->category->title}}" class="form-control" id="asset_category" name="asset_category" disabled="disabled">
        </div>
    </div>

    <div class="form-group{{ $errors->has('purchase_option_clause') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-12 control-label">Purchase Option Clause in the Contract</label>
        <div class="col-md-12 form-check form-check-inline mrktavail" required>
            <span>
                <input class="form-check-input" name="purchase_option_clause" id="yes" type="checkbox" value="yes" @if(old('purchase_option_clause', $model->purchase_option_clause) == "yes") checked="checked" @endif>
                <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label>
            </span>
            <span>
                <input class="form-check-input" name="purchase_option_clause" id="no" type="checkbox" value="no" @if(old('purchase_option_clause', $model->purchase_option_clause)  == "no") checked="checked" @endif>
                <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            </span>
            @if ($errors->has('purchase_option_clause'))
                <span class="help-block">
                        <strong>{{ $errors->first('purchase_option_clause') }}</strong>
                    </span>
            @endif
        </div>
    </div>

    <div class="hidden-group" id="hidden-field" @if(old('purchase_option_clause', $model->purchase_option_clause) == "yes") style="display:block;" @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('purchase_option_exerecisable') ? ' has-error' : '' }} required">
            <label for="name" class="col-md-12 control-label">Is the Purchase Option Exercisable</label>
            <div class="col-md-12 form-check form-check-inline mrktavail" required>
                <span>
                    <input class="form-check-input" name="purchase_option_exerecisable" id="yes" type="checkbox" value="yes" @if(old('purchase_option_exerecisable', $model->purchase_option_exerecisable) == "yes") checked="checked" @endif>
                    <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label>
                </span>
                <span>
                    <input class="form-check-input" name="purchase_option_exerecisable" id="no" type="checkbox" value="no" @if(old('purchase_option_exerecisable', $model->purchase_option_exerecisable)  == "no") checked="checked" @endif>
                    <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
                </span>
                @if ($errors->has('purchase_option_exerecisable'))
                    <span class="help-block">
                            <strong>{{ $errors->first('purchase_option_exerecisable') }}</strong>
                        </span>
                @endif
            </div>
        </div>
    </div>

     <div class="hidden-group" id="hidden-elements" @if(old('purchase_option_exerecisable', $model->purchase_option_exerecisable) == "yes" && old('purchase_option_clause',$model->purchase_option_clause) == "yes") style="display:block;" @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('expected_purchase_date') ? ' has-error' : '' }} required">
            <label for="expected_purchase_date" class="col-md-12 control-label">Expected Purchase Date</label>
            <div class="col-md-12">
                <input type="text" placeholder="Expected Purchase Date" class="form-control pull-right lease_period1" id="expected_purchase_date" name="expected_purchase_date" value="{{ old('expected_purchase_date', $model->expected_purchase_date) }}" autocomplete="off" readonly="readonly" style="opacity: 1;background-color:#fff">
                @if ($errors->has('expected_purchase_date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('expected_purchase_date') }}</strong>
                    </span>
                @endif
            </div>
        </div>

         <div class="form-group{{ $errors->has('expected_lease_end_date') ? ' has-error' : '' }} required">
            <label for="expected_lease_end_date" class="col-md-12 control-label">Expected Lease End Date</label>
            <div class="col-md-12">
                <input type="text" placeholder="Expected Lease End Date" class="form-control lease_period2" id="expected_lease_end_date" name="expected_lease_end_date" value="{{ old('expected_lease_end_date', $model->expected_lease_end_date) }}" autocomplete="off" readonly="readonly" style="opacity: 1;background-color:#fff">
                @if ($errors->has('expected_lease_end_date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('expected_lease_end_date') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }} required">
            <label for="currency" class="col-md-12 control-label">Purchase Currency</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" value="{{ $lease->lease_contract_id }}" class="form-control" id="currency" name="currency" readonly="readonly">
                @if ($errors->has('currency'))
                    <span class="help-block">
                        <strong>{{ $errors->first('currency') }}</strong>
                    </span>
                @endif
            </div>
        </div>

         <div class="form-group{{ $errors->has('purchase_price') ? ' has-error' : '' }} required">
            <label for="purchase_price" class="col-md-12 control-label">Anticipated Purchase Price</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" class="form-control" id="purchase_price" name="purchase_price" value="{{ old('purchase_price', $model->purchase_price) }}">
                @if ($errors->has('purchase_price'))
                    <span class="help-block">
                        <strong>{{ $errors->first('purchase_price') }}</strong>
                    </span>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="form-group btnMainBx">

    <div class="col-md-4 col-sm-4 btn-backnextBx">
        <a href="{{ route('addlease.renewable.index', ['id' => $lease->id]) }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
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
        $(document).on('click', 'input[name="purchase_option_clause"]', function() {
            $('input[name="purchase_option_clause"]').not(this).prop('checked', false);
            if($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-field').show();
                
            } else {
                $('#hidden-field').hide();
                 $('#hidden-fields').hide();
                 $('#hidden-elements').hide();
            }
        });

        $(document).on('click', 'input[name="purchase_option_exerecisable"]', function() {
            $('input[name="purchase_option_exerecisable"]').not(this).prop('checked', false);
            if($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-elements').show();

                bootbox.dialog({
                    message: "Please input, verify and update your Lease Payments Schedule as well under step 6 to restrict Lease Payments up to the date of Lease Purchase. Failure to do so may impact the Lease Valuation.",
                    buttons: {
                        confirm: {
                            label: 'Ok',
                            className: 'btn-success'
                        },
                    }
                });

            } else {
                $('#hidden-elements').hide();
                $('#hidden-fields').hide();
            }
        });

        $(document).on('click', 'input[name="termination_penalty_applicable"]', function() {
            $('input[name="termination_penalty_applicable"]').not(this).prop('checked', false);
            if($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-fields').show();
            } else {
                $('#hidden-fields').hide();
            }
        });

        $(document).ready(function(){

            @if(\Carbon\Carbon::parse($asset->accural_period)->greaterThan(\Carbon\Carbon::parse(getParentDetails()->baseDate->final_base_date)))
                var minDate = new Date('{{ $asset->accural_period }}');
                    @else
                var minDate = new Date('{{ getParentDetails()->baseDate->final_base_date }}');
            @endif

            $("#expected_purchase_date").datepicker({
                dateFormat: "dd-M-yy",
                changeMonth:true,
                changeYear:true,
                //minDate : new Date('{{ \Carbon\Carbon::parse($asset->accural_period)->addDay(1)->format('Y-m-d') }}'),
                minDate: minDate,
                maxDate : new Date('{{ ($asset->renewableOptionValue->is_reasonable_certainity_option == "yes")?$asset->renewableOptionValue->expected_lease_end_Date:$asset->lease_end_date }}'),
                {!!  getYearRanage() !!}
                onSelect: function (date, instance) {

                    var _ajax_url = '{{route("lease.checklockperioddate")}}';
                    checklockperioddate(date, instance, _ajax_url);

                    var expectedPurchaseDate = $(this).datepicker('getDate');
                    var expectedLeaseEndDate = $('#expected_lease_end_date');

                    var newdate = new Date(expectedPurchaseDate);
                    newdate.setDate(newdate.getDate() - 1); // minus the date

                    var dd = newdate.getDate();
                    var mm = newdate.getMonth() + 1;
                    var y = newdate.getFullYear();

                    var nd = new Date(mm + '/' + dd + '/' + y);
                    expectedLeaseEndDate.datepicker('option','maxDate',nd);
                    expectedLeaseEndDate.datepicker('option','minDate',nd);
                    expectedLeaseEndDate.datepicker('setDate',nd);
                }
            });

            $("#expected_lease_end_date").datepicker({
                dateFormat: "dd-M-yy",
                changeMonth:true,
                changeYear:true,
                minDate : new Date('{{ \Carbon\Carbon::parse($asset->accural_period)->format('Y-m-d') }}'),
                maxDate : new Date('{{ ($asset->renewableOptionValue->is_reasonable_certainity_option == "yes")?$asset->renewableOptionValue->expected_lease_end_Date:$asset->lease_end_date }}'),
                {!!  getYearRanage() !!}
                onSelect: function (date, instance) {
                        var _ajax_url = '{{route("lease.checklockperioddate")}}';
                        checklockperioddate(date, instance, _ajax_url);
                    }
            });
        });
        $('.save_next').on('click', function (e) {
                e.preventDefault();
                $('input[name="action"]').val('next');
                $('#lease_purchase').submit();
        });

    </script>
@endsection