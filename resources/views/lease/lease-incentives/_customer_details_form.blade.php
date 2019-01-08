<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Modal Header</h4>
</div>
<div class="modal-body">
    <form action="{{ route('addlease.leaseincentives.addcustomer') }}" method="post" id="customer_details_form" class="customer_details_form">
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

            @if(!empty($customer_details))
                @foreach($customer_details as $key=>$customer_detail)
                <tr>
                        <td>{{ $customer_detail['customer_name'] }}</td>
                        <td>{{ $customer_detail['description'] }}</td>
                        <td>
                         {{ $customer_detail['incentive_date'] }}      

                        </td>
                        <td>
                         {{ $customer_detail['currency_id'] }}      

                        </td>
                        <td class="customer_details_amount">{{ $customer_detail['amount'] }}</td>
                        
                        <td>{{ $customer_detail['exchange_rate'] }}</td>
                        <td>
                        <a href="javascript:void(0);" class="btn btn-sm btn-danger customer_create_details_form_delete" data-customer_id="{{$key}}"><i class="fa fa-trash-o"></i></a></td>
                     </tr>
                @endforeach
            @endif
            <tr>
                <td>
                    <input type="text" class="form-control" name="customer_name">
                </td>
                <td>
                    <input type="text" class="form-control" name="description">
                </td>
                <td>
                    <input type="text" class="form-control incentive_date" name="incentive_date" autocomplete="off">
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
  

    $(document).ready(function () {
        DatePickerIn();
     });
    function DatePickerIn(){
        console.log("Hello");                
        $('.incentive_date').datepicker({
            dateFormat: "dd-M-yy"
        });
     }
    
 </script>