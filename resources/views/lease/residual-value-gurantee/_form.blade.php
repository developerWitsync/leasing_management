<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('any_residual_value_gurantee') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Any Residual Guarantee Value</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-check-input" name="any_residual_value_gurantee" id="yes" type="checkbox" value="yes" @if(old('any_residual_value_gurantee', $model->any_residual_value_gurantee) == "yes") checked="checked" @endif>
            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label><br>
            <input class="form-check-input" name="any_residual_value_gurantee" id="no" type="checkbox" value="no" @if(old('any_residual_value_gurantee', $model->any_residual_value_gurantee)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            @if ($errors->has('any_residual_value_gurantee'))
                <span class="help-block">
                        <strong>{{ $errors->first('any_residual_value_gurantee') }}</strong>
                    </span>
            @endif
        </div>
    </div>
    <div class="hidden-group gg {{$model->any_residual_value_gurantee}}" id="hidden-fields" @if($model->any_residual_value_gurantee == "yes") style="display:block;" @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('lease_payemnt_nature_id') ? ' has-error' : '' }} required">
            <label for="lease_payemnt_nature_id" class="col-md-4 control-label">Nature of Lease Payment</label>
            <div class="col-md-6 form-check form-check-inline">
                 <select name="lease_payemnt_nature_id" id="lease_payemnt_nature_id" class="form-control">
                    <option value="">--Select Lease Payment Nature--</option>
                                            @foreach($lease_payments_nature as $nature)
                  <option value="{{ $nature->id}}" @if(old('lease_payemnt_nature_id', $nature->id) == $model->lease_payemnt_nature_id) selected="selected" @endif>{{ $nature->title }}</option>
                                            @endforeach
                </select>
                @if ($errors->has('lease_payemnt_nature_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('lease_payemnt_nature_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>
         <div class="form-group{{ $errors->has('amount_determinable') ? ' has-error' : '' }} required">
        <label for="type" class="col-md-4 control-label">Amount Determinable</label>
            <div class="col-md-6">
            <input class="form-check-input" name="amount_determinable" type="checkbox" id="amount_determinable_yes" value="yes" @if(old('amount_determinable', $model->amount_determinable)  == "yes") checked="checked" @endif ><label clas="form-check-label" for="amount_determinable_yes" style="vertical-align: 4px">Yes</label>
                        
            <input class="form-check-input" name="amount_determinable" type="checkbox" id="amount_determinable_no" value="no" @if(old('amount_determinable', $model->amount_determinable)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="amount_determinable_no" style="vertical-align: 4px">No</label>
                                    </div>
          </div>
        <div class="form-group{{ $errors->has('foreign_currency_id') ? ' has-error' : '' }} required">
            <label for="foreign_currency_id" class="col-md-4 control-label">Currency</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" value="{{ $lease->lease_contract_id }}" class="form-control" id="foreign_currency_id" name="foreign_currency_id" readonly="readonly">
                @if ($errors->has('foreign_currency_id'))
                    <span class="help-block">
                        <strong>{{ $errors->first('foreign_currency_id') }}</strong>
                    </span>
                @endif
            </div>
        </div>


        <div class="form-group{{ $errors->has('similar_asset_items') ? ' has-error' : '' }} required">
            <label for="similar_asset_items" class="col-md-4 control-label">Number of Units of Lease Assets of Similar Characteristics</label>
            <div class="col-md-6 form-check form-check-inline">
                <input type="text" value="{{ $asset->similar_asset_items }}" class="form-control" id="similar_asset_items" name="similar_asset_items" readonly="readonly">
                @if ($errors->has('similar_asset_items'))
                    <span class="help-block">
                        <strong>{{ $errors->first('similar_asset_items') }}</strong>
                    </span>
                @endif
            </div>
        </div>


        <div class="form-group{{ $errors->has('residual_gurantee_value') ? ' has-error' : '' }} required">
            <label for="residual_gurantee_value" class="col-md-4 control-label">Residual Guarantee Value Per Unit</label>
            <div class="col-md-6">
                <input type="text" placeholder="Residual Gurantee Value" class="form-control" id="residual_gurantee_value" name="residual_gurantee_value" value="{{ old('residual_gurantee_value', $model->residual_gurantee_value) }}">
                @if ($errors->has('residual_gurantee_value'))
                    <span class="help-block">
                        <strong>{{ $errors->first('residual_gurantee_value') }}</strong>
                    </span>
                @endif
            </div>
        </div>

        <div class="form-group{{ $errors->has('total_residual_gurantee_value') ? ' has-error' : '' }} required">
            <label for="total_residual_gurantee_value" class="col-md-4 control-label">Total Residual Guarantee Value</label>
            <div class="col-md-6">
                <input type="text" name="total_residual_gurantee_value" for="type" class="form-control" id="total_residual_gurantee_value" value="{{ old('total_residual_gurantee_value', $model->total_residual_gurantee_value) }}">
                @if ($errors->has('total_residual_gurantee_value'))
                    <span class="help-block">
                        <strong>{{ $errors->first('total_residual_gurantee_value') }}</strong>
                    </span>
                @endif
            </div>
        </div>
         <div class="form-group{{ $errors->has('other_desc') ? ' has-error' : '' }} ">
                                    <label for="type" class="col-md-4 control-label">Any Other Description</label>
                                    <div class="col-md-6">
                                       <textarea name="other_desc" class="form-control" > {{ old('other_desc', $model->other_desc) }}</textarea>
                                    </div>
                                </div>
        <div class="form-group{{ $errors->has('residual_file') ? ' has-error' : '' }}">
        <label for="workings_doc" class="col-md-4 control-label">Upload</label>
        <div class="col-md-6">
            <input id="workings_doc" type="file" placeholder="" class="form-control" name="residual_file">
            @if ($errors->has('residual_file'))
                <span class="help-block">
                        <strong>{{ $errors->first('residual_file') }}</strong>
                    </span>
            @endif
        </div>
    </div>
    </div>

 

    

    <div class="form-group">
        <div class="col-md-6 col-md-offset-4">

            <a href="{{ route('addlease.fairmarketvalue.index', ['id' => $lease->id]) }}" class="btn btn-danger">Cancel</a>
            <button type="submit" class="btn btn-success">
                Submit
            </button>
        </div>
    </div>

</form>

@section('footer-script')
 <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script>
        $(function(){
            
              $("#lease_payemnt_nature_id").on('change', function(){
                var value = $(this).val();
                if(value == '2')
                {
                var modal = bootbox.dialog({
                    message: "Turn Over Lease ",
                    buttons: [
                      {
                        label: "Ok",
                        className: "btn btn-success pull-left",
                        callback: function() {
                         }
                      },
                    ],
                    show: false,
                    onEscape: function() {
                      modal.modal("hide");
                    }
                });
                modal.modal("show");
            }
          });
        });
    </script>
    <script type="text/javascript">
        $(document).on('click', 'input[name="any_residual_value_gurantee"]', function() {
            $('input[name="any_residual_value_gurantee"]').not(this).prop('checked', false);

            if($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-fields').show();
            } else {
                $('#hidden-fields').hide();
            }

            /*$('input[name="amount_determinable"]').not(this).prop('checked', false);

            if($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-fields').show();
            } else {
                $('#hidden-fields').hide();
            }*/
        });

        jQuery(document).ready(function($) {
            var $total = $('#total_residual_gurantee_value'),
                $value = $('#residual_gurantee_value');
            $units = $("#similar_asset_items").val();
            $value.on('input', function(e) {
                var total = 0;
                $value.each(function(index, elem) {
                    if(!Number.isNaN(parseInt(this.value, 10)))
                        total = $units * parseInt(this.value, 10);
                });
                $total.val(total);
            });
        });
    </script>

@endsection