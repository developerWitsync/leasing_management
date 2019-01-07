<form role="form"  class="form-horizontal" method="post" enctype="multipart/form-data">
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
    <div class="form-group{{ $errors->has('currency') ? ' has-error' : '' }} ">
        <label for="name" class="col-md-4 control-label">Currency</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="currency"  type="text" value="{{$currency[0]->foreign_exchange_currency}}"  readonly="readonly">
            
        </div>
    </div>
    <div class="form-group{{ $errors->has('total_lease_incentives') ? ' has-error' : '' }} ">
        <label for="name" class="col-md-4 control-label">Total Lease Incentive</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="total_lease_incentives" id="yes" type="text" value="{{ old('total_lease_incentives', $model->total_lease_incentives) }}" >
             @if ($errors->has('total_lease_incentives'))
                <span class="help-block">
                    <strong>{{ $errors->first('total_lease_incentives') }}</strong>
                </span>
            @endif
        </div>
    </div>
   <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <a href="{{ route('addlease.renewable.index', ['id' => $lease->id]) }}" class="btn btn-danger">Cancel</a>
            <button type="submit" name="submit" class="btn btn-success">
                Submit
            </button>
        </div>
  </div>

</form>

<div class="panel panel-info">
<div class="panel-heading">Customer Details
<span><a href="javascript:void(0);" data-form="add_customer" class="btn btn-sm btn-primary pull-right add_more">Add More</a></span></div>
           <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                 
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Customer Name</th>
                            <th>Description</th>
                            <th>Currency</th>
                            <th>Amount</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($customer_details as $key => $value)
                           <tr>
                            <td>{{$key + 1}}</td>
                            <td>{{$value->customer_name}}</td>
                            <td>{{$value->description}}</td>
                            <td>{{$value->currency_id}}</td>
                            <td>{{$value->amount}}</td>
                            <td><a data-href="{{ route('addlease.leaseincentives.updateCustomer', ['id' => $value->id]) }}" href="javascript:;" class="btn btn-sm btn-success edit_table_setting2">
                             <i class="fa fa-pencil-square-o"></i>
                            </a>
                            <a data-href="{{ route('addlease.leaseincentives.deletecustomerdetails', ['id' => $value->id]) }}" href="javascript:;" class="btn btn-sm btn-danger delete_settings"><i class="fa fa-trash-o"></i></a></td>
                           </tr>
                            @endforeach
                           <tr style="{{ $errors->has('customer_name') ? ' has-error' : 'display: none' }}" class="add_customer">
                                        <td></td>
                                        <td>
                                        <form action="{{ route('addlease.leaseincentives.addcustomerdetails') }}" method="POST" class="add_customer_details_form">
                                                        {{ csrf_field() }}
                                            <div class="form-group{{ $errors->has('customer_name') ? ' has-error' : '' }}">
                                             <input type="text" value="{{ old('customer_name') }}" name="customer_name" placeholder="Customer Name" id="customer_name" class="form-control {{ $errors->has('customer_name') ? ' has-error' : '' }}"/>
                                            @if ($errors->has('customer_name'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('customer_name') }}</strong>
                                                </span>
                                            @endif
                                            </div>
                                            <div class="form-group{{ $errors->has('description') ? ' has-error' : '' }}">
                                             <input type="text" value="{{ old('description') }}" name="description" placeholder="Description" id="description" class="form-control {{ $errors->has('description') ? ' has-error' : '' }}"/>
                                            @if ($errors->has('description'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('description') }}</strong>
                                                </span>
                                            @endif
                                            </div>
                                            <div class="form-group{{ $errors->has('currency_id') ? ' has-error' : '' }}">
                                             <input type="text" value="{{ old('currency_id') }}" name="currency_id" placeholder="Currency" id="currency_id" class="form-control {{ $errors->has('currency_id') ? ' has-error' : '' }}"/>
                                            @if ($errors->has('currency_id'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('currency_id') }}</strong>
                                                </span>
                                            @endif
                                            </div>
                                            <div class="form-group{{ $errors->has('amount') ? ' has-error' : '' }}">
                                             <input type="text" value="{{ old('amount') }}" name="amount" placeholder="Amount" id="amount" class="form-control {{ $errors->has('amount') ? ' has-error' : '' }}"/>
                                            @if ($errors->has('amount'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('amount') }}</strong>
                                                </span>
                                            @endif
                                            </div>
                                             </form>
                                        </td>
                                        <td>
                                            <button type="button" onclick="javascript:$('.add_customer_details_form').submit();" class="btn btn-sm btn-success">Save</button>
                                            <a href="javascript:;" class="btn btn-sm btn-danger add_more" data-form="add_customer">Cancel</a>
                                        </td>
                                    </tr>
                        </tbody>
                    </table>
                   

                </div>
            </div>
</div>

 <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">

                </div>
            </div>
        </div>
@section('footer-script')
<script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
 <script type="text/javascript">
        $(document).on('click', 'input[type="checkbox"]', function() {
            $('input[type="checkbox"]').not(this).prop('checked', false);
         });
 $('.edit_table_setting2').on('click', function(){
        $.ajax({
            url : $(this).data('href'),
            type : 'get',
            success : function (response) {
                $('.modal-content').html(response);
                $("#myModal").modal('show');
                
            }
        });
    });

    $("#myModal").on('hidden.bs.modal', function(){
        $('.modal-content').html('');
    });
    </script>
@endsection