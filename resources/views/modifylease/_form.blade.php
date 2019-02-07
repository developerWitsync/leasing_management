<form role="form" class="form-horizontal" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}
    <div class="categoriesOuter clearfix">
    <div class="form-group{{ $errors->has('valuation') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-12 control-label">Valuation</label>
        <div class="col-md-12 form-check form-check-inline mrktavail" required>
            <span>
                <input class="form-check-input" name="valuation" id="yes" type="checkbox" value="Modify Initial Valuation" @if($disable_initial) disabled="disabled" @endif>
                <label class="form-check-label" for="yes" id="modify" style="vertical-align: 4px">Modify Initial Valuation</label><br>
            </span>
            <span>
                <input class="form-check-input" name="valuation" id="no" type="checkbox" value="Subsequent Valuation">
                <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">Subsequent Valuation</label>
            </span>
            @if ($errors->has('valuation'))
                <span class="help-block">
                        <strong>{{ $errors->first('valuation') }}</strong>
                    </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('effective_from') ? ' has-error' : '' }} effctive required" style="display: none;">
        <label for="name" class="col-md-12 control-label">Effective From</label>
        <div class="col-md-12 form-check form-check-inline" required>
            <input type="text" class="form-control" name="effective_from" id="effective_from"> 
            @if ($errors->has('effective_from'))
                <span class="help-block">
                        <strong>{{ $errors->first('effective_from') }}</strong>
                    </span>
            @endif
        </div>
    </div>

    <div class="form-group{{ $errors->has('reason') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-12 control-label">Reason For Modification</label>
        <div class="col-md-12 form-check form-check-inline" required>
             <select class="form-control" name="reason">
                <option value="">Please select reason</option>
                 @foreach($lase_modification as $lease)
                        <option value="{{ $lease->id }}">{{ $lease->title }}</option>
                    @endforeach
                </select>
            @if ($errors->has('reason'))
                <span class="help-block">
                    <strong>{{ $errors->first('reason') }}</strong>
                </span>
            @endif
        </div>
    </div>
    </div>
    <div class="form-group btnMainBx">
        <div class="col-md-6 col-sm-12 btn-backnextBx">
            <a href="{{ route('addlease.fairmarketvalue.index', ['id' => $lease->id]) }}" class="btn btn-danger">Cancel</a>
        </div>
        <div class="col-md-6 col-sm-12 btnsubmitBx">
            <button type="submit" class="btn btn-success">
                Submit
            </button>
        </div>
    </div>

</form>

@section('footer-script')
 <script src="{{ asset('js/jquery-ui.js') }}"></script>
 <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script type="text/javascript">
     $('#effective_from').datepicker({
         dateFormat: "dd-M-yy"
     })
 </script>
  <script type="text/javascript">
    $(document).on('click', 'input[name="valuation"]', function() {
            $('input[name="valuation"]').not(this).prop('checked', false);

            if($(this).is(':checked') && $(this).val() == 'Subsequent Valuation') {
                $('#hidden-fields').show();
                $('.effctive').show();
                bootbox.confirm({
                    message: "You are about to Change the Lease Valuation which may impact your financial treatment effective from Date",
                    buttons: {
                        confirm: {
                            label: 'Confirm',
                            className: 'btn-success pull-left'
                        },
                        cancel: {
                            label: 'Cancel',
                            className: 'btn-danger pull-left'
                        }
                    },
                    callback: function (result) {
                        
                    }
                });
                
            }else if($(this).is(':checked') && $(this).val() == 'Modify Initial Valuation'){
                $('#hidden-fields').hide();
                $('.effctive').hide();
                
                //$('#hidden-fields_date').hide();
               // $('input[name="is_reasonable_certainity_option"]').prop('checked', false);
                //$('input[name="is_reasonable_certainity_option"][value="no"]').prop('checked', true);
                //$('#expected_lease_end_Date').val('');
                bootbox.confirm({
                    message: "You are about to Change the Initial Valuation which may impact your financial treatment accounted based on previous valuation",
                    buttons: {
                        confirm: {
                            label: 'Confirm',
                            className: 'btn-success pull-left'
                        },
                        cancel: {
                            label: 'Cancel',
                            className: 'btn-danger pull-left'
                        }
                    },
                    callback: function (result) {
                        
                    }
                });

            }
        });

</script>
@endsection