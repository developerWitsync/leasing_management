<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Modal Header</h4>
</div>
<div class="modal-body">

    <div class="alert alert-info">
        In order to update a particular Customer Details please delete that particular Customer and add a new Customer.
    </div>

    <form action="" method="post" id="customer_details_form_update">
        {{ csrf_field()}}
        <table class="table table-bordered table_customer_details">
            <thead>
            <tr>
                <th>Customer Name</th>
                <th>Description</th>
                <th>Date</th>
                <th>Currency</th>
                <th>Amount </th>
                <th>Exchange Rate </th>
                 <th>Action</th>
            </tr>
            </thead>
            
            <tbody>
                 @foreach($directCost->customerDetails as $customer_detail)
                    <tr class="customer">
                        <td>{{ $customer_detail->customer_name }}</td>
                        <td>{{ $customer_detail->description }}</td>
                        <td>{{ $customer_detail->incentive_date }}</td>
                        <td>{{ $customer_detail->currency_id }}</td>
                        <td class="customer_details_amount">{{ $customer_detail->amount }}</td>
                        <td>{{ $customer_detail->exchange_rate }}</td>

                       <td>
                            {{--<a href="javascript:void(0);" class="btn btn-sm btn-success customer_details_form_edit"><i class="fa fa-pencil-square-o"></i></a>--}}
                            &nbsp;
                            <a href="javascript:void(0);" class="btn btn-sm btn-danger customer_details_form_delete" data-lease_id="{{ $directCost->lease_id }}" data-customer_id="{{ $customer_detail->id }}"><i class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td>
                        <input type="hidden" name="lease_incentive_id" value="{{ $directCost->id }}">
                        <input type="text" class="form-control" name="customer_name">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="description">
                    </td>
                     <td>
                        <input type="text" class="form-control incentive_date" name="incentive_date">
                    </td>
                    <td>
                        <select name="currency_id" class="form-control">
                    <option value="">--Please Select</option>
                     @foreach($currencies as $currency)
                            <option value="{{ $currency->code }}">{{ $currency->code }}  {{ $currency->symbol }}</option>
                        @endforeach               
                    </select>
                    </td>
                    <td>
                        <input type="text" class="form-control" name="amount">
                    </td>
                    <td>
                        <input type="text" class="form-control" name="exchange_rate">
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
     $('.incentive_date').datepicker({
         dateFormat: "dd-M-yy"
     });
 </script>