<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('initial_direct_cost_involved') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Any Initial Direct Cost Involved</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-check-input" name="initial_direct_cost_involved" id="yes" type="checkbox" value="yes" @if(old('initial_direct_cost_involved', $model->initial_direct_cost_involved) == "yes") checked="checked" @endif>
            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label><br>
            <input class="form-check-input" name="initial_direct_cost_involved" id="no" type="checkbox" value="no" @if(old('initial_direct_cost_involved', $model->initial_direct_cost_involved)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            @if ($errors->has('initial_direct_cost_involved'))
                <span class="help-block">
                        <strong>{{ $errors->first('initial_direct_cost_involved') }}</strong>
                    </span>
            @endif
        </div>
    </div>
         <div class="hidden-group" id="hidden-fields" @if(old('initial_direct_cost_involved',$model->initial_direct_cost_involved ) == "yes") style="display:block;" @else  style="display:none;" @endif>
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


        <div class="form-group{{ $errors->has('total_initial_direct_cost') ? ' has-error' : '' }} required">
            <label for="total_initial_direct_cost" class="col-md-4 control-label">Total Initial Direct Cost</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" value="{{ $asset->total_initial_direct_cost }}" class="form-control" id="total_initial_direct_cost" name="total_initial_direct_cost" readonly="readonly">
                @if ($errors->has('total_initial_direct_cost'))
                    <span class="help-block">
                        <strong>{{ $errors->first('total_initial_direct_cost') }}</strong>
                    </span>
                @endif
            </div>
        </div>

    <div class="form-group{{ $errors->has('details') ? ' has-error' : '' }}">
        <label for="details" class="col-md-4 control-label"></label>
        <div class="col-md-6">
            <a data-toggle="modal" data-target="#myModal" href="javascript:void(0);" class="btn btn-primary">Enter Details</a>
        </div>
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


<div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Modal Header</h4>
              </div>
              <div class="modal-body">
                    <form action="{{ route('addlease.initialdirectcost.createsupplier') }}" method="post" id="supplier_details">
                        {{ csrf_field()}}
                        <table class="table table-bordered">
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
                                <tr>
                                    @if(empty($supplierData))
                                        <td><input type="text" class="form-control" name="supplier_name[]">  </td>
                                        <td><input type="text" class="form-control" name="direct_cost[]">  </td>
                                        <td><input type="text" class="form-control" name="expense_date[]">  </td>
                                        <td><input type="text" class="form-control" name="currency[]">  </td>
                                        <td><input type="text" class="form-control" name="amount[]">  </td>
                                        <td><input type="text" class="form-control" name="rate[]">  </td>
                                        <td>
                                            <button type="button" class="btn btn-success" data-dismiss="modal">Save</button>
                                        </td>
                                    @else

                                    @endif
                                </tr>
                            </tbody>
                        </table>
                    </form>
              </div>
              <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
              </div>
            </div>

          </div>
        </div>

@section('footer-script')
    <script type="text/javascript">
        $(document).on('click', 'input[type="checkbox"]', function() {
            $('input[type="checkbox"]').not(this).prop('checked', false);

            if($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-fields').show();
            } else {
                $('#hidden-fields').hide();
            }
        });
    </script>
@endsection