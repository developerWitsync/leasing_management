<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Modal Header</h4>
</div>
<div class="modal-body">

    <div class="alert alert-info">
        In order to update a particular Supplier Details please delete that particular Supplier and add a new Supplier.
    </div>

    <form action="" method="post" id="supplier_details_form_update">
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

                @foreach($directCost->supplierDetails as $supplier_detail)
                    <tr>
                        <td>{{ $supplier_detail->supplier_name }}</td>
                        <td>{{ $supplier_detail->direct_cost_description }}</td>
                        <td>{{ $supplier_detail->expense_date }}</td>
                        <td>{{ $supplier_detail->supplier_currency }}</td>
                        <td class="supplier_details_amount">{{ $supplier_detail->amount }}</td>
                        <td>{{ $supplier_detail->rate }}</td>
                        <td>
                            {{--<a href="javascript:void(0);" class="btn btn-sm btn-success supplier_details_form_edit"><i class="fa fa-pencil-square-o"></i></a>--}}
                            &nbsp;
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger supplier_details_form_delete" data-lease_id="{{ $directCost->lease_id }}" data-supplier_id="{{ $supplier_detail->id }}"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td>
                        <input type="hidden" name="initial_direct_cost_id" value="{{ $directCost->id }}">
                        <input type="text" class="form-control" name="supplier_name">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="direct_cost_description">
                    </td>
                    <td>
                        <input type="text" class="form-control expense_date" name="expense_date" id= "expense_date">
                    </td>
                    <td>
                        <!-- <input type="text" class="form-control" name="currency"> -->
                         <select class="form-control" name="supplier_currency">
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