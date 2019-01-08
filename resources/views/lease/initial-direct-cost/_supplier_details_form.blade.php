<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Modal Header</h4>
</div>
<div class="modal-body">
    <form action="{{ route('addlease.initialdirectcost.addsupplier') }}" method="post" id="supplier_details_form">
        {{ csrf_field()}}
        <table class="table table-bordered table_supplier_details">
            <thead>
            <tr>
                <th>Supplier Name</th>
                <th>Initial Direct Cost Description</th>
                <th>Date of Expense</th>
                <th>Currency</th>
                <th>Amount </th>
                <th>Exchange Rate</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            @if(!empty($supplier_details))
                @foreach($supplier_details as $key=>$supplier_detail)
                    <tr>
                        <td>{{ $supplier_detail['supplier_name'] }}</td>
                        <td>{{ $supplier_detail['direct_cost_description'] }}</td>
                        <td>{{ $supplier_detail['expense_date'] }}</td>
                        <td>{{ $supplier_detail['currency'] }}</td>
                        <td class="supplier_details_amount">{{ $supplier_detail['amount'] }}</td>
                        <td>{{ $supplier_detail['rate'] }}</td>
                        <td>
                        <a href="javascript:void(0);" class="btn btn-sm btn-danger supplier_create_details_form_delete" data-supplier_id="{{$key}}"><i class="fa fa-trash-o"></i></a></td> 
                    </tr>
                @endforeach
            @endif
            <tr>
                <td>
                    <input type="text" class="form-control" name="supplier_name">
                </td>
                <td>
                    <input type="text" class="form-control" name="direct_cost_description">
                </td>
                <td>
                    <input type="text" class="form-control expense_date" name="expense_date" id= "expense_date">
                </td>
                <td>
                    <select class="form-control" name="currency">
            <option value="">--Select Currency--</option>
            @foreach($currencies as $currency)
                <option value="{{ $currency->code }}">{{ $currency->code }}  {{ $currency->symbol }}</option>
            @endforeach
        </select>
                </td>
                <td>
                    <input type="text" class="form-control" name="amount">
                </td>
                <td>
                    <input type="text" class="form-control" name="rate">
                </td>
                <td>
                    <button type="submit" class="btn btn-success">Save</button>
                </td>
            </tr>
            </tbody>
        </table>
    </form>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
</div>
<script src="{{ asset('js/jquery-ui.js') }}"></script>
<script type="text/javascript">
    $('.expense_date').datepicker({
                dateFormat: 'dd-M-yy'
            });
</script>