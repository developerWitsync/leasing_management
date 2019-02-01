
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach (array_unique($errors->all()) as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('is_any_lease_incentives_receivable') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Any Lease Incentives Receivable</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-check-input" name="is_any_lease_incentives_receivable" id="yes" type="checkbox" value="yes" @if(old('is_any_lease_incentives_receivable', $model->is_any_lease_incentives_receivable) == "yes") checked="checked" @endif>
            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label><br>
            <input class="form-check-input" name="is_any_lease_incentives_receivable" id="no" type="checkbox" value="no" @if(old('is_any_lease_incentives_receivable', $model->is_any_lease_incentives_receivable)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            @if ($errors->has('is_any_lease_incentives_receivable'))
                <span class="help-block">
                        <strong>{{ $errors->first('is_any_lease_incentives_receivable') }}</strong>
                    </span>
            @endif
        </div>
    </div>
         <div class="hidden-group" id="hidden-fields" @if(old('is_any_lease_incentives_receivable',$model->is_any_lease_incentives_receivable ) == "yes") style="display:block;" @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }} required">
            <label for="currency" class="col-md-4 control-label">Currency</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" value="{{ $lease->lease_contract_id }}" class="form-control" id="currency" name="currency" readonly="readonly">
                @if ($errors->has('currency'))
                    <span class="help-block">
                        <strong>{{ $errors->first('currency') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('total_lease_incentives') ? ' has-error' : '' }} required">
            <label for="total_lease_incentives" class="col-md-4 control-label">Total Initial Direct Cost</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" value="{{ old('total_lease_incentives', $model->total_lease_incentives)}}" class="form-control" id="total_lease_incentives" name="total_lease_incentives" readonly="readonly">
                @if ($errors->has('total_lease_incentives'))
                    <span class="help-block">
                        <strong>{{ $errors->first('total_lease_incentives') }}</strong>
                    </span>
                @endif
            </div>
        </div>

    <div class="row">
            <table class="table table-bordered table-condensed add_more_table">
                <thead>
                    <th>Customer Name</th>
                    <th>Description</th>
                    <th>Date</th>
                    <th>Currency</th>
                    <th>Amount </th>
                    <th>Exchange Rate </th>
                    <th>Action</th>
                </thead>
                <tbody>
<?php //dd($model->customerDetails)?>
                    @if(count($model->customerDetails) > 0)
                        @foreach($model->customerDetails as $customerDetail)
                            <tr class="clonable_row customer">
                                <td>
                                    <input type="text" class="form-control" name="customer_name[]" value="{{ $customerDetail->customer_name }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="description[]" value="{{ $customerDetail->description }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control incentive_date" name="incentive_date[]" value="{{ \Carbon\Carbon::parse($customerDetail->incentive_date)->format('Y-m-d') }}">
                                </td>
                                <td>
                                    <select class="form-control" name="currency_id[]">
                                        <option value="">--Select Currency--</option>
                                        @foreach($currencies as $currency)
                                            <option value="{{ $currency->code }}" @if($customerDetail->currency_id == $currency->code) selected="selected" @endif>{{ $currency->code }}  {{ $currency->symbol }}</option>
                                        @endforeach
                                    </select>
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="amount[]" value="{{ $customerDetail->amount }}">
                                </td>
                                <td>
                                    <input type="text" class="form-control" name="exchange_rate[]" value="{{ $customerDetail->exchange_rate }}">
                                </td>
                                <td>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-danger customer_create_details_form_delete" onClick="javascript:removeRow(this)">Remove</a>
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
                                <input type="text" class="form-control incentive_date" name="incentive_date[]">
                            </td>
                            <td>
                                <select class="form-control" name="currency_id[]">
                                    <option value="">--Select Currency--</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->code }}">{{ $currency->code }}  {{ $currency->symbol }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="amount[]">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="exchange_rate[]">
                            </td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger customer_create_details_form_delete" onClick="javascript:removeRow(this)">Remove</a>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <a href="javascript:void(0)" onclick="javascript:addMore(this)" class="btn btn-sm right btn-success add_more"><i class="fa fa-plus-square"></i> Add More</a>
        </div>
     </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">

            <a href="{{ route('addlease.leaseincentives.index', ['id' => $lease->id]) }}" class="btn btn-danger">Cancel</a>
            <button type="submit" class="btn btn-success">
                Submit
            </button>
        </div>
    </div>
</form>

<!-- Modal -->
<div id="myModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content _form_customer_details">
        </div>
    </div>
</div>

@section('footer-script')
<script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
<script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script type="text/javascript">

        $(document).on('click', 'input[type="checkbox"]', function() {
            $('input[type="checkbox"]').not(this).prop('checked', false);

            if($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-fields').show();
            } else {
                $('#hidden-fields').hide();
            }
        });
        $(function () {
            $('.incentive_date').datepicker();
        });


        function addMore(that){

            var cloned_html = '<tr class="customer clonable_row">\n' +
                '                <td>\n' +
                '                    <input type="text" class="form-control" name="customer_name[]">\n' +
                '                </td>\n' +
                '                <td>\n' +
                '                    <input type="text" class="form-control" name="description[]">\n' +
                '                </td>\n' +
                '                <td>\n' +
                '                    <input type="text" class="form-control cale_n_dar" name="incentive_date[]">\n' +
                '                </td>\n' +
                '                <td>\n' +
                '                    <select class="form-control" name="currency_id[]">\n' +
                '                        <option value="">--Select Currency--</option>\n' +
                '                        @foreach($currencies as $currency)\n' +
                '                            <option value="{{ $currency->code }}">{{ $currency->code }}  {{ $currency->symbol }}</option>\n' +
                '                        @endforeach\n' +
                '                    </select>\n' +
                '                </td>\n' +
                '                <td>\n' +
                '                    <input type="text" class="form-control" name="amount[]">\n' +
                '                </td>\n' +
                '                <td>\n' +
                '                    <input type="text" class="form-control" name="exchange_rate[]">\n' +
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
                    beforeShow: function() {
                        setTimeout(function() {
                            $('.ui-datepicker').css('z-index', 99999999999999);

                        }, 0);
                    }
                });
        }

        function removeRow(that){
        var rowCount =  $('tr.customer').length;
            if(rowCount == 1){
                var modal = bootbox.dialog({
                message: 'You can not delete this detail to do this you have to Enter lease incentives details',
                buttons: [
                {
                    label: "OK",
                    className: "btn btn-success pull-left",
                    callback: function() {
                    }
                }
                ],
                    show: false,
                    onEscape: function() {
                    modal.modal("hide");
                    }
                });
                modal.modal("show");
            }
            else {
               $(that).parent('td').parent('tr').remove();
            }
        }

    </script>
@endsection