@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
<?php //dd($reporting_foreign_currency_transaction_settings);?>
@section('content')
        <div class="panel panel-default">
            <div class="panel-heading">Add New Lease | Lessor Details</div>

            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @include('add-new-lease._menubar')

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active">
                        <form class="form-horizontal" method="POST" action="{{ route('add-new-lease.index.save') }}" enctype="multipart/form-data">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('lessor_name') ? ' has-error' : '' }} required">
                                <label for="lessor_name" class="col-md-4 control-label">Lessor Name</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input id="lessor_name" type="text" placeholder="Lessor Name" class="form-control" name="lessor_name" value="{{ old('lessor_name', isset($settings->lessor_name)? $settings->lessor_name:"") }}" >
                                     </div>
                                    @if ($errors->has('lessor_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lessor_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                              <div class="form-group{{ $errors->has('lease_type_id') ? ' has-error' : '' }} required">
                                <label for="lease_type_id" class="col-md-4 control-label">Lease Type Classification</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                       
                                       <select name="lease_type_id" class="form-control" id="bootbox">
                                        <option value="">Please Type Classification</option>
                                         {{ $i =1}}
                                         @foreach($contract_classifications as $classification)
                                         <option class="cla-{{$i}}" value="{{ $classification->id }}">
                                            {{ $classification->title }} </option>
                                               {{$i++}} 
                                            @endforeach
                                    </select>
                                     </div>
                                    @if ($errors->has('lease_type_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_type_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="panel panel-info">
                              <div class="panel-heading">Foreign Currency Transaction </div>
                              <div class="panel-body">
                                  <div class="form-group{{ $errors->has('statutory_financial_reporting_currency') ? ' has-error' : '' }} required">
                                      <label for="statutory_financial_reporting_currency" class="col-md-6 control-label">Transactions in Foreign Currency Involved</label>
                                      <div class="col-md-6">

                                              <div class="col-md-6 form-check form-check-inline">
                                                  <input class="form-check-input" name="is_involved" @if($reporting_currency_settings->is_foreign_transaction_involved == 'yes')  checked="checked" @endif type="checkbox" id="is_involved_yes" value="yes">
                                                  <label class="form-check-label" for="is_involved_yes" style="vertical-align: 4px">Yes</label>
                                              </div>

                                              <div class=" col-md-6 form-check form-check-inline">
                                                  <input class="form-check-input" name="is_involved" @if($reporting_currency_settings->is_foreign_transaction_involved == 'no')  checked="checked" @endif type="checkbox" id="is_involved_no" value="no">
                                                  <label class="form-check-label" for="is_involved_no" style="vertical-align: 4px">No</label>
                                              </div>
                                      </div>
                                  </div>
                              </div>
                            </div>
@if($reporting_currency_settings->is_foreign_transaction_involved == "yes")
                          <div class="form-group{{ $errors->has('lease_contract_id') ? ' has-error' : '' }} required">
                                <label for="lease_contract_id" class="col-md-4 control-label">Lease Contract Currency</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                       <select name="lease_contract_id" class="form-control">
                                        <option value="">Please Type Lease Contract</option>

                                        @foreach($reporting_foreign_currency_transaction_settings as $currencies)
                                         <option value="{{ $currencies->id }}">
                                            {{ $currencies->foreign_exchange_currency }}{{ '('.$currencies->base_currency.')' }}</option>
                                             @endforeach
                                    </select>
                                     </div>
                                    @if ($errors->has('lease_contract_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_contract_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                          </div> 
@endif
@if($reporting_currency_settings->is_foreign_transaction_involved == "no")
  <div class="form-group{{ $errors->has('lease_contract_id') ? ' has-error' : '' }} required">
                                <label for="lease_contract_id" class="col-md-4 control-label">Lease Contract Currency</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                       <select name="lease_contract_id" class="form-control">
                                        <option value="">Please Type Lease Contract</option>

                                        @foreach($currencies as $currencies)
                                         <option value="{{ $currencies->id }}">
                                            {{ $currencies->code }}{{ '('.$currencies->symbol.')' }}</option>
                                             @endforeach
                                    </select>
                                     </div>
                                    @if ($errors->has('lease_contract_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_contract_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                          </div> 
@endif
                             <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }} ">
                                <label for="file" class="col-md-4 control-label">Upload a Copy of Contract Signed</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                      <input type="file" name="file" class="form-control">
                                     </div>
                                    @if ($errors->has('file'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('file') }}</strong>
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

          <div class="form-content" style="display:none;">
             <span>Any Non lease component also exist</span>
         </div>
          <div class="form-content1" style="display:none;">
             <span>if Non lease component exist,Please Select single lease and non lease contract</span>
         </div>
@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function() {
  $("#bootbox").on("click", function(event) {
  var value = $(this).children("option:selected").val();
   if(value == '1')
   { 
        var modal = bootbox.dialog({
            message: $(".form-content").html(),
            buttons: [
              {
                label: "Yes",
                className: "btn btn-primary pull-left",
                callback: function() {
                    secondPopUp();
                    return true;
                }
              },
              {
                label: "No",
                className: "btn btn-default pull-left",
                callback: function() {
                  console.log("just do something on close");
                }
              }
            ],
            show: false,
            onEscape: function() {
              modal.modal("hide");
            }
        });
        
        modal.modal("show");
   }
});
function secondPopUp(){
        var modal = bootbox.dialog({
            message: $(".form-content1").html(),
            buttons: [
              {
                label: "Yes",
                className: "btn btn-primary pull-left",
                callback: function() {
                }
              },
              {
                label: "No",
                className: "btn btn-default pull-left",
                callback: function() {
                  console.log("just do something on close");
                }
              }
            ],
            show: false,
            onEscape: function() {
              modal.modal("hide");
            }
        });
        
        modal.modal("show");
    }
});
</script>
@endsection
