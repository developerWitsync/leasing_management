@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
    <script type="text/javascript">
        $(document).on('click', 'input[type="checkbox"]', function() {      
    $('input[type="checkbox"]').not(this).prop('checked', false);      
});
jQuery(document).ready(function($) {
    var $total = $('#total'),
        $value = $('#unit');
        $units = $("#items").text(); 
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
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Fair Market Value of Underlying Lease Asset</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach (array_unique($errors->all()) as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{--@include('lease._menubar')--}}

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>UNDERLYING LEASE ASSET NUMBER</th>
                            <th>Unique ULA Code</th>
                            <th>Underlying Leased Asset Classification</th>
                            <th>Name of the Underlying Lease Asset</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lease->assets as $key=>$asset)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td style="width: 10%">
                                    {{ $asset->uuid}}
                                </td>
                                <td>
                                    {{ $asset->subcategory->title }}
                                </td>
                                <td>
                                    {{ $asset->name }}
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="panel panel-default">

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                        <div class="stepwizard">
                            <div class="stepwizard-row setup-panel">
                                @for($i = 1;$i <= count($lease->assets); $i++)
                                    <div class="stepwizard-step">
                                        <a href="#step-1" type="button" class="btn @if($i == 1) btn-primary @else btn-default @endif btn-circle">{{ $i }}</a>
                                    </div>
                                @endfor
                            </div>
                        </div>

                       <form role="form" class="form-horizontal" action="{{ route('addlease.fairmarketvalue.save') }}" method="post">
                                {{ csrf_field() }}
                                <input type="hidden" name="asset_id" value="{{ $asset->id}}">
                                <input type="hidden" name="lease_id"  value="{{ $lease->id }}">
                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} required"> 
                                    <label for="name" class="col-md-4 control-label">Is Market Value Available</label>
                                    <div class="col-md-6 form-check form-check-inline" required>
                                            <input class="form-check-input" name="is_market_value_available" id="yes" type="checkbox"  value="yes">
                                            <label class="form-check-label" for="yes" id="yes" style="vertical-align: 4px">Yes</label><br>
                                            <input class="form-check-input" name="is_market_value_available" id="no" type="checkbox" value="no">
                                            <label class="form-check-label" for="no" id="no" style="vertical-align: 4px">No</label>
                                        </div>
                                </div>
                                <div class="hidden-group"  id="hidden-fields">
                                <div class="form-group">
                                    <label  for="type" class="col-md-4 control-label">Currency</label>
                                    @if($reporting_currency_settings->is_foreign_transaction_involved == "yes")
                                    <label  for="type" class="col-md-1">{{$reporting_foreign_currency_transaction_settings->foreign_exchange_currency->base_currency}}   </label>
                                    @endif
                                    @if($reporting_currency_settings->is_foreign_transaction_involved == "no")
                                    <label  for="type" class="col-md-1">{{$reporting_currency_settings->currency_for_lease_reports}}</label>
                                    @endif
                                </div>

                                <div class="form-group">
                                    <label for="type" class="col-md-4 control-label">Number of Units of Lease Assets of Similar Characteristics</label>
                                    <label for="type" class="col-md-1" id= "items">{{$asset->similar_asset_items}}</label>
                                </div>

                                <label for="name" class="col-md-4 control-label">Enter FMV Per Unit</label>
                                    <div class="col-md-6">
                                        <input type="text" placeholder="Units" class="form-control" id="unit" name="unit" value="{{ old('name') }}">
                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('name') }}</strong>
                                            </span>
                                        @endif
                                    </div>

                                    <div class="form-group">
                                    <label for="type" class="col-md-4 control-label">Total FMV</label>
                                    <div class="col-md-6">
                                    <input type="text" name="total" for="type" class="form-control" id = "total">
                                    </div>
                                </div>
                            </div>


                                <div class="form-group{{ $errors->has('source') ? ' has-error' : '' }}">
                                    <label for="source" class="col-md-4 control-label">Enter SOURCE OF FMV</label>
                                    <div class="col-md-6">
                                        <input id="source" type="text" placeholder="Source" class="form-control" name="source">
                                        @if ($errors->has('source'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('source') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
        <div class="col-md-6 col-md-offset-4">
            <button type="submit" class="btn btn-success">
                Submit
            </button>
        </div>
    </div>

                        </form>
                </div>
            </div>



        </div>
    </div>
    </div>

<!--     <script>
function myFunction() {
  document.getElementById("yes").required = true;
  document.getElementById("no").required = true;
  var checkBox = document.getElementById("yes");
  var text = document.getElementById("text");
  if (checkBox.checked == true){
    text.style.display = "block";
  } else {
     text.style.display = "none";
  }
}
</script> -->
    
    
@endsection

@section('footer-script')