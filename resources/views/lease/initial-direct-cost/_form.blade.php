@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach (array_unique($errors->all()) as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data" id="lease_initial">
    {{ csrf_field() }}
    <div class="categoriesOuter clearfix">
  

    <div class="form-group required">
        <label for="asset_name" class="col-md-12 control-label">Lease Asset Name</label>
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

    <input type="hidden" name="id" value="{{ $model->id }}">

    <div class="form-group{{ $errors->has('initial_direct_cost_involved') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-12 control-label">Any Initial Direct Cost Involved</label>
        <div class="col-md-12 form-check form-check-inline mrktavail" required>
            <span>
                <input class="form-check-input" name="initial_direct_cost_involved" id="yes" type="checkbox" value="yes"
                   @if(old('initial_direct_cost_involved', $model->initial_direct_cost_involved) == "yes") checked="checked" @endif>
                <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label>
            </span>
            <span>
                <input class="form-check-input" name="initial_direct_cost_involved" id="no" type="checkbox" value="no"
                   @if(old('initial_direct_cost_involved', $model->initial_direct_cost_involved)  == "no") checked="checked" @endif>
                <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            </span>
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


        <div class="form-group{{ $errors->has('total_initial_direct_cost') ? ' has-error' : '' }} required">
            <label for="total_initial_direct_cost" class="col-md-12 control-label">Total Initial Direct Cost</label>
            <div class="col-md-12 form-check form-check-inline">
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
            <div class="col-md-12 directcostTble">
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
                                <input type="text" class="form-control lease_period expense_date" name="expense_date[]" value="{{ \Carbon\Carbon::parse($supplier->expense_date)->format(config('settings.date_format')) }}">
                            </td>
                            <td>
                                <select class="form-control supplier_currency" name="supplier_currency[]">
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
                                <input type="text" class="form-control rate" name="rate[]" value="{{ $supplier->rate }}">
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
                            <input type="text" class="form-control lease_period expense_date" name="expense_date[]">
                        </td>
                        <td>
                            <select class="form-control supplier_currency" name="supplier_currency[]">
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
                            <input type="text" class="form-control rate" name="rate[]">
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

    </div>
    </div>
    <div class="form-group btnMainBx">
 <div class="col-md-4 col-sm-4 btn-backnextBx">
        <a href="{{ $back_url }}" class="btn btn-danger"><i class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>
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
            $('.expense_date').datepicker({
                dateFormat: "dd-M-yy",
                changeMonth:true,
                changeYear:true,
                onSelect: function (date, instance) {
                        var _ajax_url = '{{route("lease.checklockperioddate")}}';
                        checklockperioddate(date, instance, _ajax_url);
                    }
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
                '                            <input type="text" class="form-control expense_date lease_period1" name="expense_date[]">\n' +
                '                        </td>\n' +
                '                        <td>\n' +
                '                            <select class="form-control supplier_currency" name="supplier_currency[]">\n' +
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
                '                            <input type="text" class="form-control rate" name="rate[]">\n' +
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
                    changeMonth:true,
                    changeYear:true,
                     onSelect: function (date, instance) {
                        var _ajax_url = '{{route("lease.checklockperioddate")}}';
                        checklockperioddate(date, instance, _ajax_url);
                    },
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
            } else {
               $(that).parent('td').parent('tr').remove();
            }
        }
        $('.save_next').on('click', function (e) {
            
                e.preventDefault();
                $('input[name="action"]').val('next');
                $('#lease_initial').submit();
        });

        /**
         * fetches the currency rates from the Currency API
         */
        $(document.body).on('change', '.supplier_currency', function(){
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