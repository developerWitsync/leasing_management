<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

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
                    <tr class="clonable_row">
                        <td>
                            <input type="text" class="form-control" name="supplier_name[]">
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
                            <a href="javascript:void(0)" class="btn btn-sm btn-danger">Remove</a>
                            <a href="javascript:void(0)" onclick="javascript:addMore(this)" class="btn btn-sm btn-success add_more"><i class="fa fa-plus-square"></i> Add More</a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

    </div>

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">

            <a href="{{ route('addlease.initialdirectcost.index', ['id' => $lease->id]) }}" class="btn btn-danger">Cancel</a>
            <button type="submit" class="btn btn-success">
                Submit
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

        $('.enter_supplier_details').on('click', function () {
            $.ajax({
                @if(request()->segment('2') == 'initial-direct-cost' && request()->segment('3') == 'update')
                url: '{{ route("addlease.initialdirectcost.updatesupplier", ['id' => $model->id]) }}',
                @else
                url: '{{ route("addlease.initialdirectcost.addsupplier") }}',
                @endif
                type: 'get',
                success: function (response) {
                    $('._form_supplier_details').html(response);

                    $("#myModal").modal('show');
                }
            });
        });



        $(function () {
            initialiseDatepickers();
        });

        function initialiseDatepickers(){
            $('.expense_date').each(function(){
                $(this).datepicker();
            });
        }


        function addMore(that){
            var newRow = $('.clonable_row:eq(-1)').clone().insertAfter($('.clonable_row:last'));
            newRow.find("input.expense_date")
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
            $(that).remove();
            initialiseDatepickers();
        }

    </script>
@endsection