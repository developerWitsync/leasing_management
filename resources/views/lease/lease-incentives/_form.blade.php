@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach (array_unique($errors->all()) as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data" id="lease_incentive">
    {{ csrf_field() }}
    <div class="categoriesOuter clearfix">
    
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

    <div class="form-group{{ $errors->has('is_any_lease_incentives_receivable') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-12 control-label">Any Lease Incentives Receivable</label>
        <div class="col-md-12 form-check form-check-inline  mrktavail" required>
            <span>
                <input class="form-check-input" name="is_any_lease_incentives_receivable" id="yes" type="checkbox"
                   value="yes"
                   @if(old('is_any_lease_incentives_receivable', $model->is_any_lease_incentives_receivable) == "yes") checked="checked" @endif @if($subsequent_modify_required) disabled="disabled" @endif>
                <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label>
            </span>
            <span>
                <input class="form-check-input" name="is_any_lease_incentives_receivable" id="no" type="checkbox" value="no"
                   @if(old('is_any_lease_incentives_receivable', $model->is_any_lease_incentives_receivable)  == "no") checked="checked" @endif @if($subsequent_modify_required) disabled="disabled" @endif>
                <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            </span>

            @if($subsequent_modify_required)
                <input type="hidden" name="is_any_lease_incentives_receivable" value="{{$model->is_any_lease_incentives_receivable}}" />
            @endif

            @if ($errors->has('is_any_lease_incentives_receivable'))
                <span class="help-block">
                    <strong>{{ $errors->first('is_any_lease_incentives_receivable') }}</strong>
                </span>
            @endif
        </div>
    </div>
    <div class="hidden-group" id="hidden-fields"
         @if(old('is_any_lease_incentives_receivable',$model->is_any_lease_incentives_receivable ) == "yes") style="display:block;"
         @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }} required">
            <label for="currency" class="col-md-12 control-label">Currency</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" value="{{ $lease->lease_contract_id }}" class="form-control" id="currency"
                       name="currency" readonly="readonly" @if($subsequent_modify_required) disabled="disabled" @endif>

                @if($subsequent_modify_required)
                    <input type="hidden" name="currency" value="{{$model->lease_contract_id}}" />
                @endif

                @if ($errors->has('currency'))
                    <span class="help-block">
                        <strong>{{ $errors->first('currency') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('total_lease_incentives') ? ' has-error' : '' }} required">
            <label for="total_lease_incentives" class="col-md-12 control-label">Total Lease Incentive Receivable</label>
            <div class="col-md-12 form-check form-check-inline">
                <input type="text" value="{{ old('total_lease_incentives', $model->total_lease_incentives)}}"
                       class="form-control" id="total_lease_incentives" name="total_lease_incentives"
                       readonly="readonly" @if($subsequent_modify_required) disabled="disabled" @endif>

                @if($subsequent_modify_required)
                    <input type="hidden" name="total_lease_incentives" value="{{$model->total_lease_incentives}}" />
                @endif

                @if ($errors->has('total_lease_incentives'))
                    <span class="help-block">
                        <strong>{{ $errors->first('total_lease_incentives') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 directcostTble">
            <table class="table table-bordered table-condensed add_more_table">
                <thead>
                <th>Customer Name</th>
                <th>Description</th>
                <th>Date</th>
                <th>Currency</th>
                <th>Amount</th>
                <th>Exchange Rate</th>
                <th>Action</th>
                </thead>
                <tbody>
                @if(count($model->customerDetails) > 0)
                    @foreach($model->customerDetails as $customerDetail)
                        <tr class="clonable_row customer">
                            <td>
                                <input type="text" class="form-control" name="customer_name[]"
                                       value="{{ $customerDetail->customer_name }}" @if($subsequent_modify_required) disabled="disabled" @endif>

                                @if($subsequent_modify_required)
                                    <input type="hidden" name="customer_name[]" value="{{ $customerDetail->customer_name }}"/>
                                @endif

                            </td>
                            <td>
                                <input type="text" class="form-control" name="description[]"
                                       value="{{ $customerDetail->description }}" @if($subsequent_modify_required) disabled="disabled" @endif>
                                @if($subsequent_modify_required)
                                    <input type="hidden" name="description[]" value="{{ $customerDetail->description }}"/>
                                @endif
                            </td>
                            <td>
                                <input type="text" class="form-control lease_period incentive_date" name="incentive_date[]"
                                       value="{{ \Carbon\Carbon::parse($customerDetail->incentive_date)->format(config('settings.date_format')) }}" @if($subsequent_modify_required) disabled="disabled" @endif>

                                @if($subsequent_modify_required)
                                    <input type="hidden" name="incentive_date[]" value="{{ \Carbon\Carbon::parse($customerDetail->incentive_date)->format(config('settings.date_format')) }}"/>
                                @endif

                            </td>
                            <td>
                                <select class="form-control customer_currency" name="currency_id[]" @if($subsequent_modify_required) disabled="disabled" @endif>
                                    <option value="">--Select Currency--</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency }}"
                                                @if($customerDetail->currency_id == $currency) selected="selected" @endif>{{ $currency }} </option>
                                    @endforeach
                                </select>

                                @if($subsequent_modify_required)
                                    <input type="hidden" value="{{$customerDetail->currency_id}}" name="currency_id[]"/>
                                @endif

                            </td>
                            <td>
                                <input type="text" class="form-control" name="amount[]"
                                       value="{{ $customerDetail->amount }}" @if($subsequent_modify_required) disabled="disabled" @endif>
                                @if($subsequent_modify_required)
                                    <input type="hidden" value="{{$customerDetail->amount}}" name="amount[]"/>
                                @endif
                            </td>
                            <td>
                                <input type="text" class="form-control rate" name="exchange_rate[]"
                                       value="{{ $customerDetail->exchange_rate }}" @if($subsequent_modify_required) disabled="disabled" @endif>

                                @if($subsequent_modify_required)
                                    <input type="hidden" value="{{$customerDetail->exchange_rate}}" name="exchange_rate[]"/>
                                @endif

                            </td>
                            <td>
                                <a href="javascript:void(0);"
                                   class="btn btn-sm btn-danger customer_create_details_form_delete"
                                   @if($subsequent_modify_required) onClick="javascript:void(0)" @else onClick="javascript:removeRow(this)" @endif
                                   @if($subsequent_modify_required) disabled="disabled" @endif>Remove</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="clonable_row customer">
                        <td>
                            <input type="text" class="form-control" name="customer_name[]">
                            @if ($errors->has('customer_name[]'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('customer_name[]') }}</strong>
                                    </span>
                            @endif
                        </td>
                        <td>
                            <input type="text" class="form-control" name="description[]">
                        </td>
                        <td>
                            <input type="text" class="form-control lease_period incentive_date" name="incentive_date[]" autocomplete="off">
                        </td>
                        <td>
                            <select class="form-control customer_currency" name="currency_id[]">
                                <option value="">--Select Currency--</option>
                                @foreach($currencies as $currency)
                                    <option value="{{ $currency }}">{{ $currency }}</option>
                                @endforeach
                            </select>
                        </td>
                        <td>
                            <input type="text" class="form-control" name="amount[]">
                        </td>
                        <td>
                            <input type="text" class="form-control rate" name="exchange_rate[]">
                        </td>
                        <td>
                            <a href="javascript:void(0);"
                               class="btn btn-sm btn-danger customer_create_details_form_delete"
                               @if($subsequent_modify_required) onClick="javascript:void(0)" @else onClick="javascript:removeRow(this)" @endif
                               @if($subsequent_modify_required) disabled="disabled" @endif>Remove</a>
                        </td>
                    </tr>
                @endif
                </tbody>
            </table>
            @if($subsequent_modify_required)
                    <a href="javascript:void(0)"
                       class="btn btn-sm right btn-success add_more" disabled="disabled"><i class="fa fa-plus-square"></i> Add More</a>
            @else
                    <a href="javascript:void(0)" onclick="javascript:addMore(this)"
                       class="btn btn-sm right btn-success add_more"><i class="fa fa-plus-square"></i> Add More</a>
            @endif
            </div>
        </div>
    </div>
</div>
<div class="form-group btnMainBx">
 <div class="col-md-4 col-sm-4 btn-backnextBx">
        <a href="{{ route('addlease.initialdirectcost.index', ['id' => $lease->id]) }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
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
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script type="text/javascript">

        $(document).on('click', 'input[type="checkbox"]', function () {
            $('input[type="checkbox"]').not(this).prop('checked', false);

            if ($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-fields').show();
            } else {
                $('#hidden-fields').hide();
            }
        });
        $(function () {
            $('.incentive_date').datepicker({
                dateFormat: "dd-M-yy",
                changeMonth:true,
                changeYear:true,
                onSelect: function (date, instance) {
                        var _ajax_url = '{{route("lease.checklockperioddate")}}';
                        checklockperioddate(date, instance, _ajax_url);
                    }
            });
        });


        function addMore(that) {

            var cloned_html = '<tr class="customer clonable_row">\n' +
                '                <td>\n' +
                '                    <input type="text" class="form-control" name="customer_name[]">\n' +
                '                </td>\n' +
                '                <td>\n' +
                '                    <input type="text" class="form-control" name="description[]">\n' +
                '                </td>\n' +
                '                <td>\n' +
                '                    <input type="text" class="form-control cale_n_dar lease_period2" name="incentive_date[]" autocomplete="off">\n' +
                '                </td>\n' +
                '                <td>\n' +
                '                    <select class="form-control customer_currency" name="currency_id[]">\n' +
                '                        <option value="">--Select Currency--</option>\n' +
                '                        @foreach($currencies as $currency)\n' +
                '                            <option value="{{ $currency }}">{{ $currency}} </option>\n' +
                '                        @endforeach\n' +
                '                    </select>\n' +
                '                </td>\n' +
                '                <td>\n' +
                '                    <input type="text" class="form-control" name="amount[]">\n' +
                '                </td>\n' +
                '                <td>\n' +
                '                    <input type="text" class="form-control rate" name="exchange_rate[]">\n' +
                '                </td>\n' +
                '                <td>\n' +
                '                    <a href="javascript:void(0);" class="btn btn-sm btn-danger customer_create_details_form_delete" onClick="javascript:removeRow(this)">Remove</a>\n' +
                '                </td>\n' +
                '            </tr>';

            var newRow = $(cloned_html).insertAfter($('.clonable_row:last'));

            newRow.find("input.cale_n_dar")
                .removeClass('hasDatepicker')
                .removeData('datepicker')
                .unbind()
                .datepicker({
                    dateFormat: "dd-M-yy",
                    changeYear:true,
                    changeMonth:true,
                     onSelect: function (date, instance) {
                        var _ajax_url = '{{route("lease.checklockperioddate")}}';
                        checklockperioddate(date, instance, _ajax_url);
                    },
                    beforeShow: function () {
                        setTimeout(function () {
                            $('.ui-datepicker').css('z-index', 99999999999999);

                        }, 0);
                    }
                });
        }

        function removeRow(that) {
            var rowCount = $('tr.customer').length;
            if (rowCount == 1) {
                var modal = bootbox.dialog({
                    message: 'You can not delete this detail to do this you have to Enter lease incentives details',
                    buttons: [
                        {
                            label: "OK",
                            className: "btn btn-success pull-left",
                            callback: function () {
                            }
                        }
                    ],
                    show: false,
                    onEscape: function () {
                        modal.modal("hide");
                    }
                });
                modal.modal("show");
            } else {
                $(that).parent('td').parent('tr').remove();
            }
        }
         $('.save_next').on('click', function (e) {
                e.preventDefault();
                $('input[name="action"]').val('next');
                $('#lease_incentive').submit();
        });

        /**
         * fetches the currency rates from the Currency API
         */
        $(document.body).on('change', '.customer_currency', function(){
            var selected_currency = $(this).val();
            var base_currency = '{{ $lease->lease_contract_id }}';
            var that = $(this);
            // set endpoint and your access key
            var endpoint = 'live';
            var access_key = '{{ env("CURRENCY_API_ACCESS_KEY") }}';
            // get the most recent exchange rates via the "live" endpoint:
            $.ajax({
                url: 'http://apilayer.net/api/' + endpoint + '?access_key=' + access_key + '&source='+base_currency+'&currencies='+selected_currency,
                dataType: 'jsonp',
                success: function(result) {
                    if(result.success) {
                        var rate = result['quotes'][base_currency+selected_currency];
                        $(that).parent('td').parent('tr').find('input.rate').val(rate);
                    }
                }
            });
        });

    </script>
@endsection