<form role="form"  class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('is_renewal_option_under_contract') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Lease Renewal Option Available Under the Contract</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-check-input" name="is_renewal_option_under_contract" id="yes" type="checkbox" value="yes" @if(old('is_renewal_option_under_contract', $model->is_renewal_option_under_contract) == "yes") checked="checked" @endif>
            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label><br>
            <input class="form-check-input" name="is_renewal_option_under_contract" id="no" type="checkbox" value="no" @if(old('is_renewal_option_under_contract', $model->is_renewal_option_under_contract)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
            @if ($errors->has('is_renewal_option_under_contract'))
                <span class="help-block">
                        <strong>{{ $errors->first('is_renewal_option_under_contract') }}</strong>
                    </span>
            @endif
        </div>
    </div>
    <div class="hidden-group gg {{$model->is_renewal_option_under_contract}}" id="hidden-fields" @if(old('is_renewal_option_under_contract',$model->is_renewal_option_under_contract ) == "yes") style="display:block;" @else  style="display:none;" @endif>
       
         <div class="form-group{{ $errors->has('is_reasonable_certainity_option') ? ' has-error' : '' }} required">
        <label for="type" class="col-md-4 control-label">Reasonable Certainity to Exercise Renewal Option as of today</label>
            <div class="col-md-6">
            <input class="form-check-input" name="is_reasonable_certainity_option" type="checkbox" id="is_reasonable_certainity_option_yes" value="yes" @if(old('is_reasonable_certainity_option', $model->is_reasonable_certainity_option)  == "yes") checked="checked" @endif ><label clas="form-check-label" for="is_reasonable_certainity_option_yes" style="vertical-align: 4px">Yes</label>
                        
            <input class="form-check-input" name="is_reasonable_certainity_option" type="checkbox" id="is_reasonable_certainity_option_no" value="no" @if(old('is_reasonable_certainity_option', $model->is_reasonable_certainity_option)  == "no") checked="checked" @endif>
            <label class="form-check-label" for="is_reasonable_certainity_option_no" style="vertical-align: 4px">No</label>
            @if ($errors->has('is_reasonable_certainity_option'))
                    <span class="help-block">
                        <strong>{{ $errors->first('is_reasonable_certainity_option') }}</strong>
                    </span>
                @endif
            </div>
          </div>
    </div>
    <div class="hidden-group gg {{$model->is_reasonable_certainity_option}}" id="hidden-fields_date" @if(old('is_reasonable_certainity_option',$model->is_reasonable_certainity_option ) == "yes") style="display:block;" @else  style="display:none;" @endif>
        <div class="form-group{{ $errors->has('expected_lease_end_Date') ? ' has-error' : '' }} required">
            <label for="expected_lease_end_Date" class="col-md-4 control-label">Expected Lease End Date</label>
            <div class="col-md-6 form-check form-check-inline">
               <input type="text" class="form-control" id="expected_lease_end_Date" name="expected_lease_end_Date"  value="{{old('expected_lease_end_Date', $model->expected_lease_end_Date)}}">
                @if ($errors->has('expected_lease_end_Date'))
                    <span class="help-block">
                        <strong>{{ $errors->first('expected_lease_end_Date') }}</strong>
                    </span>
                @endif
            </div>
        </div>
        <div class="form-group">
            <label for="confirm" class="col-md-4 control-label"></label>
            <div class="col-md-6 form-check form-check-inline">
               <span id="confirm" name="confirm" class="btn btn-primary">Confirm
           </span>
            </div>
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

@section('footer-script')
 <script src="{{ asset('js/jquery-ui.js') }}"></script>
 <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
     <script type="text/javascript">
        $(document).on('click', 'input[name="is_renewal_option_under_contract"]', function() {
            $('input[name="is_renewal_option_under_contract"]').not(this).prop('checked', false);

            if($(this).is(':checked') && $(this).val() == 'yes') {
                $('#hidden-fields').show();
            }
            else if($(this).is(':checked') && $(this).val() == 'no') 
            {
                var modal = bootbox.dialog({
                    message: "Are you sure that extension option not available under the contract",
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
            else {

                 $('#hidden-fields').hide();
            }
        });
         $(document).on('click', 'input[name="is_reasonable_certainity_option"]', function() {
            $('input[name="is_reasonable_certainity_option"]').not(this).prop('checked', false);

            if($(this).is(':checked') && $(this).val() == 'yes') {
                 $('#hidden-fields_date').show();
                $("#is_reasonable_certainity_option_no").prop('checked', false);
            } else {
                 $('#hidden-fields_date').hide();
              $("#is_reasonable_certainity_option_yes").prop('checked', false);
            }

            
        });
         var date = new Date('<?php echo $asset->lease_end_date;?>');
          // add a day
         date.setDate(date.getDate() + 1);
         $('#expected_lease_end_Date').datepicker({

                    dateFormat: "dd-M-yy",
                    startDate: date,
                    minDate: date,

                    
                });

 $("#confirm").on("click", function(e) {
  e.preventDefault();
    var modal = bootbox.dialog({
                    message: "The lease payments till the end of the renewable period will be considered for valuation. If you would like to not consider renewal period under lease valuation, please select no to exercise renewal option",
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
  });
        
    </script>

@endsection