<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data" id="lease_fair">
    {{ csrf_field() }}
    <div class="categoriesOuter clearfix">


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


        <div class="form-group{{ $errors->has('any_security_applicable') ? ' has-error' : '' }} required">
            <label for="name" class="col-md-12 control-label">Any Security Deposit</label>
            <div class="col-md-12 form-check form-check-inline mrktavail " required>
                <span><input class="form-check-input" name="any_security_applicable" id="yes" type="checkbox"
                             value="yes"
                             @if(old('any_security_applicable', $model->any_security_applicable) == "yes") checked="checked" @endif>
                <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label></span>
                <span><input class="form-check-input" name="any_security_applicable" id="no" type="checkbox" value="no"
                             @if(old('any_security_applicable', $model->any_security_applicable)  == "no") checked="checked" @endif>
                <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label></span>
                @if ($errors->has('any_security_applicable'))
                    <span class="help-block">
                            <strong>{{ $errors->first('any_security_applicable') }}</strong>
                        </span>
                @endif
            </div>
        </div>

        <div class="hidden-group" id="hidden-fields"
             @if(old('any_security_applicable',$model->any_security_applicable ) == "yes") style="display:block;"
             @else  style="display:none;" @endif>

            <div class="form-group{{ $errors->has('refundable_or_adjustable') ? ' has-error' : '' }} required">
                <label for="name" class="col-md-12 control-label">Is Security Deposit Refundable or Adjustable at Lease
                    End</label>
                <div class="col-md-12 form-check form-check-inline mrktavail " required>
                <span><input class="form-check-input" name="refundable_or_adjustable" id="refundable" type="checkbox"
                             value="1"
                             @if(old('refundable_or_adjustable', $model->refundable_or_adjustable) == "1") checked="checked" @endif>
                <label class="form-check-label" for="refundable" style="vertical-align: 4px">Refundable</label></span>
                    <span><input class="form-check-input" name="refundable_or_adjustable" id="adjustable"
                                 type="checkbox" value="2"
                                 @if(old('refundable_or_adjustable', $model->refundable_or_adjustable)  == "2") checked="checked" @endif>
                <label class="form-check-label" for="adjustable" style="vertical-align: 4px">Adjustable</label></span>
                    @if ($errors->has('refundable_or_adjustable'))
                        <span class="help-block">
                            <strong>{{ $errors->first('refundable_or_adjustable') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group{{ $errors->has('payment_date_of_security_deposit') ? ' has-error' : '' }} required">
                <label for="payment_date_of_security_deposit" class="col-md-12 control-label">Date of Payment of Security Deposit</label>
                <div class="col-md-12 form-check form-check-inline">
                    <input type="text" value="{{ old('payment_date_of_security_deposit', $model->payment_date_of_security_deposit) }}" class="form-control" id="payment_date_of_security_deposit"
                           name="payment_date_of_security_deposit">
                    @if ($errors->has('payment_date_of_security_deposit'))
                        <span class="help-block">
                            <strong>{{ $errors->first('payment_date_of_security_deposit') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

            <div class="form-group{{ $errors->has('type_of_security_deposit') ? ' has-error' : '' }} required">
                <label for="payment_date_of_security_deposit" class="col-md-12 control-label">Type of Security Deposit</label>
                <div class="col-md-12 form-check form-check-inline">
                    <select class="form-control" name="type_of_security_deposit" id="type_of_security_deposit">
                        <option value="">--Select Type Of Security Deposit--</option>
                        <option value="Money Transfer" @if(old('type_of_security_deposit', $model->type_of_security_deposit) == "Money Transfer") selected="selected" @endif>Money Transfer</option>
                        <option value="Security Cheque" @if(old('type_of_security_deposit', $model->type_of_security_deposit) == "Security Cheque") selected="selected" @endif>Security Cheque</option>
                        <option value="Bank Guarantee" @if(old('type_of_security_deposit', $model->type_of_security_deposit) == "Bank Guarantee") selected="selected" @endif>Bank Guarantee</option>
                        <option value="Other" @if(!in_array(old('type_of_security_deposit', $model->type_of_security_deposit), [
                            'Money Transfer',
                            'Security Cheque',
                            'Bank Guarantee'
                        ])) selected="selected" @endif>Other</option>
                    </select>

                    <br>
                    <input type="text" class="form-control hidden-textbox" name="type_of_security_deposit" style="display:none;" value="{{old('type_of_security_deposit', $model->type_of_security_deposit)}}"/>

                    @if ($errors->has('type_of_security_deposit'))
                        <span class="help-block">
                            <strong>{{ $errors->first('type_of_security_deposit') }}</strong>
                        </span>
                    @endif
                </div>
            </div>


            <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }}">
                <label for="doc" class="col-md-12 control-label">Upload Copy of Security Deposit</label>
                <div class="col-md-12 frmattachFile">
                    <input type="name" id="upload2" name="name" class="form-control" disabled="disabled">
                    <button type="button" class="browseBtn">Browse</button>
                    <!-- <input type="file" id="file-name" name="file" class=""> -->
                    <input id="workings_doc" type="file" placeholder="" class="form-control fileType" name="file">
                    <h6 class="disabled">{{ config('settings.file_size_limits.file_validation') }}</h6>
                    @if ($errors->has('file'))
                        <span class="help-block">
                            <strong>{{ $errors->first('file') }}</strong>
                        </span>
                    @endif
                </div>
                @if($model->doc !='')
                    <a href="{{asset('uploads/'.$model->doc)}}" class="downloadIcon"
                       target="_blank"><i class="fa fa-download"></i></a>
                @endif
            </div>

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

            <div class="form-group{{ $errors->has('total_amount') ? ' has-error' : '' }} required">
                <label for="total_amount" class="col-md-12 control-label">Total Amount of Security Deposit</label>
                <div class="col-md-12 form-check form-check-inline">
                    <input type="text" value="{{ old('total_amount', $model->total_amount) }}" class="form-control" id="total_amount"
                           name="total_amount" >
                    @if ($errors->has('total_amount'))
                        <span class="help-block">
                            <strong>{{ $errors->first('total_amount') }}</strong>
                        </span>
                    @endif
                </div>
            </div>

        </div>

    </div>
    <div class="form-group btnMainBx">

        <div class="col-md-4 col-sm-4 btn-backnextBx">
            <a href="{{$back_url }}" class="btn btn-danger"><i
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
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script type="text/javascript">
        $(document).on('click', 'input[name="any_security_applicable"]', function () {
            $('input[name="any_security_applicable"]').not(this).prop('checked', false);
        });

        $(function(){
            $("#payment_date_of_security_deposit").datepicker({
                dateFormat: "dd-M-yy",
                changeYear: true,
                changeMonth: true,
                minDate: new Date('{{ $asset->lease_accural_period }}'),
                maxDate: new Date(),
                {!!  getYearRanage() !!}
                onSelect: function (date, instance) {
                    var _ajax_url = '{{route("lease.checklockperioddate")}}';
                    checklockperioddate(date, instance, _ajax_url);
                }
            });

            $('#type_of_security_deposit').on('change', function() {
                var changed = this,
                    check = changed.value.toLowerCase() === "other";

                if(check){
                    $('.hidden-textbox').show();
                    $(changed).next().toggle(check).prop('required', check);
                } else {
                    $('.hidden-textbox').val(changed.value);
                    $('.hidden-textbox').hide();
                }
            }).change();

        });

        $(document).on('click', 'input[name="refundable_or_adjustable"]', function () {
            $('input[name="refundable_or_adjustable"]').not(this).prop('checked', false);

            if($(this).is(':checked') && $(this).val() == "1"){
                var that = $(this);
                bootbox.confirm({
                    message: "Are you sure that the security deposit is refundable in full amount at the end of the lease?",
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

                        } else {
                            $(that).prop('checked', false)
                        }
                    }
                });
            }

            if($(this).is(':checked') && $(this).val() == "2"){
                var that = $(this);
                bootbox.confirm({
                    message: "Are you sure that the security deposit is adjustable whether in full or in part at the end of the lease against the lease payments?",
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

                        } else {
                            $(that).prop('checked', false)
                        }
                    }
                });
            }

        });

        $(document).on('click', 'input[name="any_security_applicable"]', function () {
            if ($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-fields').show();
            } else {
                $('#hidden-fields').hide();
            }
        })


        $('.save_next').on('click', function (e) {
            e.preventDefault();
            $('input[name="action"]').val('next');
            $('#lease_fair').submit();
        });
    </script>
@endsection

