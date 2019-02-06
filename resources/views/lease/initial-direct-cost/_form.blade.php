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

    <div class="form-group required">
        <label for="uuid" class="col-md-4 control-label">ULA Code</label>
        <div class="col-md-4 form-check form-check-inline">
            <input type="text" value="{{ $asset->uuid}}" class="form-control" id="uuid" name="uuid" disabled="disabled">
        </div>
    </div>

    <div class="form-group required">
        <label for="asset_name" class="col-md-4 control-label">Asset Name</label>
        <div class="col-md-4 form-check form-check-inline">
            <input type="text" value="{{ $asset->name}}" class="form-control" id="asset_name" name="asset_name" disabled="disabled">
        </div>
    </div>

    <div class="form-group required">
        <label for="asset_category" class="col-md-4 control-label">Lease Asset Classification</label>
        <div class="col-md-4 form-check form-check-inline">
            <input type="text" value="{{ $asset->category->title}}" class="form-control" id="asset_category" name="asset_category" disabled="disabled">
        </div>
    </div>

    <input type="hidden" name="id" value="{{ $model->id }}">

    <div class="form-group{{ $errors->has('initial_direct_cost_involved') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Any Initial Direct Cost Involved</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-check-input" name="initial_direct_cost_involved" id="yes" type="checkbox" value="yes"
                   @if(old('initial_direct_cost_involved', $model->initial_direct_cost_involved) == "yes") checked="checked" @endif>
            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label><br>
            <input class="form-check-input" name="initial_direct_cost_involved" id="no" type="checkbox" value="no"
                   @if(old('initial_direct_cost_involved', $model->initial_direct_cost_involved)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            @if ($errors->has('initial_direct_cost_involved'))
                <span class="help-block">
                        <strong>{{ $errors->first('initial_direct_cost_involved') }}</strong>
                    </span>
            @endif
        </div>
    </div>
    <div class="hidden-group" id="hidden-fields"
         @if(old('initial_direct_cost_involved',$model->initial_direct_cost_involved ) == "yes") style="display:block;"
         @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }} required">
            <label for="currency" class="col-md-4 control-label">Currency</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" value="{{ $lease->lease_contract_id }}" class="form-control" id="currency"
                       name="currency" readonly="readonly">
                @if ($errors->has('currency'))
                    <span class="help-block">
                        <strong>{{ $errors->first('currency') }}</strong>
                    </span>
                @endif
            </div>
        </div>


        <div class="form-group{{ $errors->has('total_initial_direct_cost') ? ' has-error' : '' }} required">
            <label for="total_initial_direct_cost" class="col-md-4 control-label">Total Initial Direct Cost</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" value="{{ old('total_initial_direct_cost', $model->total_initial_direct_cost)}}"
                       class="form-control" id="total_initial_direct_cost" name="total_initial_direct_cost"
                       readonly="readonly">
                @if ($errors->has('total_initial_direct_cost'))
                    <span class="help-block">
                        <strong>{{ $errors->first('total_initial_direct_cost') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="row">
            <table class="table table-bordered table-condensed add_more_table">
                <thead>
                    <th>Supplier Name</th>
                    <th>Initial Direct Cost Description</th>
                    <th>Date of Expense</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Exchange Rate</th>
                    <th>Action</th>
                </thead>
                <tbody>
                @if(count($model->supplierDetails) > 0)
                    @foreach($model->supplierDetails as $supplier)
                        <tr class="clonable_row supplier">
                            <td>
                                <input type="text" class="form-control" name="supplier_name[]" value="{{ $supplier->supplier_name }}">
                                 @if ($errors->has('supplier_name[]'))
                                    <span class="help-block">
                                        <strong>{{ $errors->first('supplier_name[]') }}</strong>
                                    </span>
                                @endif
                            </td>
                            <td>
                                <input type="text" class="form-control" name="direct_cost_description[]" value="{{ $supplier->direct_cost_description }}">
                            </td>
                            <td>
                                <input type="text" class="form-control expense_date" name="expense_date[]" value="{{ \Carbon\Carbon::parse($supplier->expense_date)->format(config('settings.date_format')) }}">
                            </td>
                            <td>
                                <select class="form-control" name="supplier_currency[]">
                                    <option value="">--Select Currency--</option>
                                    @foreach($currencies as $currency)
                                        <option value="{{ $currency->code }}" @if($currency->code == $supplier->supplier_currency) selected="selected" @endif>{{ $currency->code }}  {{ $currency->symbol }}</option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" class="form-control" name="amount[]" value="{{ $supplier->amount }}">
                            </td>
                            <td>
                                <input type="text" class="form-control" name="rate[]" value="{{ $supplier->rate }}">
                            </td>
                            <td>
                                <a href="javascript:void(0);" class="btn btn-sm btn-danger supplier_create_details_form_delete" onClick="javascript:removeRow(this)">Remove</a>
                            </td>
                        </tr>
                    @endforeach
                @else
                    <tr class="clonable_row supplier">
                        <td>
                            <input type="text" class="form-control" name="supplier_name[]">
                            @if ($errors->has('supplier_name[]'))
                                <span class="help-block">
                                        <strong>{{ $errors->first('supplier_name[]') }}</strong>
                                    </span>
                            @endif
                        </td>
                        <td>
                            <input type="text" class="form-control" name="direct_cost_description[]">
                        </td>
                        <td>
                            <input type="text" class="form-control expense_date" name="expense_date[]">
                        </td>
                        <td>
                            <select class="form-control" name="supplier_currency[]">
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
                            <input type="text" class="form-control" name="rate[]">
                        </td>
                        <td>
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger supplier_create_details_form_delete" onClick="javascript:removeRow(this)">Remove</a>


                        </td>
                    </tr>
                @endif
                </tbody>
            </table>

            <a href="javascript:void(0)" onclick="javascript:addMore(this)" class="btn btn-sm right btn-success add_more"><i class="fa fa-plus-square"></i> Add More</a>
        </div>

    </div>

    <div class="form-group btnMainBx">
        <div class="col-md-6 btn-backnextBx">

            <a href="{{ route('addlease.balanceasondec.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>
            @if($asset->initialDirectCost)
                <a href="{{ route('addlease.leaseincentives.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
            @endif

        </div>
        <div class="col-md-6 btnsubmitBx">
            <button type="submit" name="submit" class="btn btn-success">
                Save
            </button>
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
            $('.expense_date').datepicker({
                dateFormat: "dd-M-yy",
            });
        });


        function addMore(that){

            var cloned_html = '<tr class="clonable_row supplier">\n' +
                '                        <td>\n' +
                '                            <input type="text" class="form-control" name="supplier_name[]">\n' +
                '                        </td>\n' +
                '                        <td>\n' +
                '                            <input type="text" class="form-control" name="direct_cost_description[]">\n' +
                '                        </td>\n' +
                '                        <td>\n' +
                '                            <input type="text" class="form-control expense_date" name="expense_date[]">\n' +
                '                        </td>\n' +
                '                        <td>\n' +
                '                            <select class="form-control" name="supplier_currency[]">\n' +
                '                                <option value="">--Select Currency--</option>\n' +
                '                                @foreach($currencies as $currency)\n' +
                '                                    <option value="{{ $currency->code }}">{{ $currency->code }}  {{ $currency->symbol }}</option>\n' +
                '                                @endforeach\n' +
                '                            </select>\n' +
                '                        </td>\n' +
                '                        <td>\n' +
                '                            <input type="text" class="form-control" name="amount[]">\n' +
                '                        </td>\n' +
                '                        <td>\n' +
                '                            <input type="text" class="form-control" name="rate[]">\n' +
                '                        </td>\n' +
                '                        <td>\n' +
                '                            <a href="javascript:void(0);" class="btn btn-sm btn-danger supplier_create_details_form_delete" onClick="javascript:removeRow(this)">Remove</a>\n' +
                '                            \n' +
                '                            \n' +
                '                        </td>\n' +
                '                    </tr>';
            var newRow = $(cloned_html).insertAfter($('.clonable_row:last'));
            newRow.find("input.expense_date")
                .removeClass('hasDatepicker')
                .removeData('datepicker')
                .unbind()
                .datepicker({
                    dateFormat: "dd-M-yy",
                    beforeShow: function() {
                        setTimeout(function() {
                            $('.ui-datepicker').css('z-index', 99999999999999);

                        }, 0);
                    }
                });
        }

        function removeRow(that){
        var rowCount =  $('tr.supplier').length;
            if(rowCount == 1){
                var modal = bootbox.dialog({
                message: 'You can not delete this detail to do this you have to Select No Initial direct cost',
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