<form role="form"  class="form-horizontal" method="post">
    {{ csrf_field() }}

    <div class="form-group{{ $errors->has('lease_start_date') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Lease Start Date</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="lease_start_date" id="lease_start_date" type="text" autocomplete="off"  value="{{old('lease_start_date', $asset->lease_start_date)}}" readonly="off">
           @if ($errors->has('lease_start_date'))
                <span class="help-block">
                        <strong>{{ $errors->first('lease_start_date') }}</strong>
                    </span>
            @endif
        </div>
    </div>
   <div class="form-group{{ $errors->has('lease_end_date') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Lease End Date</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control lease_end_date" name="lease_end_date" id="lease_end_date" type="text" autocomplete="off"  value="{{old('lease_end_date', $model->lease_end_date)}}">
           @if ($errors->has('lease_end_date'))
                <span class="help-block">
                        <strong>{{ $errors->first('lease_end_date') }}</strong>
                    </span>
            @endif
           
           @if($renewal_asset[0]->is_renewal_option_under_contract == 'yes' && $renewal_asset[0]->is_reasonable_certainity_option == 'yes')
            <input  name="renewalH_expected_lease_end_date" id="renewalH_expected_lease_end_date" type="hidden" value="{{ $renewal_asset[0]->expected_lease_end_Date}}">
            @endif

            @if($termination_asset[0]->lease_termination_option_available == 'yes' && $termination_asset[0]->exercise_termination_option_available == 'yes')
            <input  name="terminationG_lease_end_date" id="terminationG_lease_end_date" type="hidden" value="{{$termination_asset[0]->lease_end_date}}">
            @endif

             @if($purchase_option_asset[0]->purchase_option_clause == 'yes' && $purchase_option_asset[0]->purchase_option_exerecisable == 'yes')
            <input  name="purchaseI_lease_end_date" id="purchaseI_lease_end_date" type="hidden" value="{{$purchase_option_asset[0]->expected_lease_end_date}}">
            @endif

            <input  name="accural_period" id="accural_period" type="hidden" value="{{$asset->accural_period}}">
        </div>
    </div>
    <div class="form-group{{ $errors->has('lease_classified') ? ' has-error' : '' }} required">
        <label for="name" class="col-md-4 control-label">Lease Classified</label>
        <div class="col-md-6 form-check form-check-inline" required>
            <input class="form-control" name="lease_classified" id="yes" type="text" autocomplete="off"  value="{{old('lease_classified', $model->lease_classified)}}">
           @if ($errors->has('lease_classified'))
                <span class="help-block">
                        <strong>{{ $errors->first('lease_classified') }}</strong>
                    </span>
            @endif
        </div>
    </div>
<div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <a href="{{ route('addlease.durationclassified.index', ['id' => $lease->id]) }}" class="btn btn-danger">Cancel</a>
            <button type="submit"  class="btn btn-success">
                Submit
            </button>
        </div>
  </div>
</form>
@section('footer-script')
 <script src="{{ asset('js/jquery-ui.js') }}"></script>
 <script type="text/javascript">
    var expected_lease_end_Date = $('#renewalH_expected_lease_end_date').val();
    var lease_end_date = '<?php echo $asset->lease_end_date;?>';
    console.log(expected_lease_end_Date);
    console.log(lease_end_date);
     if(expected_lease_end_Date){
    var date = new Date(expected_lease_end_Date);
    date.setDate(date.getDate() + 1);
    $('.lease_end_date').datepicker({
        dateFormat: "d-M-yy",
        startDate: date,
        minDate: date,


    });
  }
    else if(lease_end_date){
        var date = new Date(lease_end_date);
      date.setDate(date.getDate() + 1);
      $('.lease_end_date').datepicker({
          dateFormat: "d-M-yy",
          startDate: date,
          minDate: date,

    });
      //$( ".lease_end_date" ).datepicker( "destroy" );
    }
 
 
 </script>

@endsection