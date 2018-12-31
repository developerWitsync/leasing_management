@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Add New Lease | Residual Value Guarantee - {{ $asset->name }}</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                   <div>
                        Asset Name : <span class="badge badge-success">{{ $asset->name }}</span>
                    </div>
                    <div>
                        Unique ULA Code : <span class="badge badge-primary">{{ $asset->uuid }}</span>
                    </div>


                    <div class="row form-group" style="margin-top: 12px;">
                        <div class="col-md-4">
                            <label for="no_of_lease_payments">Any Residual Value</label>
                        </div>
                        <div class="col-md-8">
                           <input class="form-check-input" name="residual_gurantee" type="checkbox" id="residual_gurantee_yes" value="yes"><label clas="form-check-label" for="residual_gurantee_yes" style="vertical-align: 4px">Yes</label>
                        
                                    <input class="form-check-input" name="residual_gurantee" type="checkbox" id="residual_gurantee_no" value="no">
                                    <label class="form-check-label" for="residual_gurantee_no" style="vertical-align: 4px">No</label>
                        </div>
                    </div>

                      <!---start the for when checkbox is checked---------->
            <div id="residualclass" style="display:none;">
                  <form role="form" enctype="multipart/form-data" method="post" class="form-horizontal" action="{{ route('addresidualvalue.residual.completedetails', ['lease_id' => $lease->id, 'asset_id'=>$asset->id]) }}" >

                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">If Yes</legend>

                                <div class="form-group{{ $errors->has('nature') ? ' has-error' : '' }} required">
                                    <label for="type" class="col-md-4 control-label">Nature of Lease Payment</label>
                                    <div class="col-md-6">
                                        <select name="lease_payemnt_nature_id" id="lease_payemnt_nature_id" class="form-control">
                                            <option value="">--Select Lease Payment Nature--</option>
                                            @foreach($lease_payments_nature as $nature)
                                                <option value="{{ $nature->id}}">{{ $nature->title }}</option>
                                            @endforeach
                                        </select>
                                        @if ($errors->has('nature'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('nature') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('nature') ? ' has-error' : '' }} required">
                                    <label for="type" class="col-md-4 control-label">Amount Determinable</label>
                                    <div class="col-md-6">
                                        <input class="form-check-input" name="amount_determinable_yes" type="checkbox" id="amount_determinable_yes" value="yes"><label clas="form-check-label" for="amount_determinable_yes_yes" style="vertical-align: 4px">Yes</label>
                        
                                    <input class="form-check-input" name="amount_determinable_no" type="checkbox" id="amount_determinable_no" value="no">
                                    <label class="form-check-label" for="amount_determinable_no" style="vertical-align: 4px">No</label>
                                    </div>
                                </div>
                                 <div class="form-group{{ $errors->has('nature') ? ' has-error' : '' }} required">
                                    <label for="type" class="col-md-4 control-label">Currency</label>
                                    <div class="col-md-6">
                                        @if($reporting_currency_settings->is_foreign_transaction_involved == "yes") 
                                        <select name="foreign_currency" id="foreign_currency" class="form-control">
                                            <option value="">--Select Lease Currency--</option>
                                            @foreach($foreign_currency_if_yes as $currency)
                                                <option value="{{ $currency->id}}">{{ $currency->foreign_exchange_currency }}</option>
                                            @endforeach
                                        </select>
                                        @endif
                                    </div>
                                </div>
                                 <div class="form-group{{ $errors->has('nature') ? ' has-error' : '' }} required">
                                    <label for="type" class="col-md-4 control-label">Number of Units of Lease Assets of Similar Characteristics</label>
                                    <div class="col-md-6">
                                       <input type="text" name="no_of_unit_lease_asset" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('nature') ? ' has-error' : '' }} required">
                                    <label for="type" class="col-md-4 control-label">Residual Guarantee Value Per Unit</label>
                                    <div class="col-md-6">
                                       <input type="text" name="residual_gurantee_value" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('nature') ? ' has-error' : '' }} required">
                                    <label for="type" class="col-md-4 control-label">Total Residual Guarantee Value</label>
                                    <div class="col-md-6">
                                       <input type="text" name="total_residual_gurantee_value" class="form-control">
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('nature') ? ' has-error' : '' }} required">
                                    <label for="type" class="col-md-4 control-label">Any Other Description</label>
                                    <div class="col-md-6">
                                       <textarea name="other_desc" class="form-control"> </textarea>
                                    </div>
                                </div>
                                <div class="form-group{{ $errors->has('nature') ? ' has-error' : '' }} required">
                                    <label for="type" class="col-md-4 control-label">Upload</label>
                                    <div class="col-md-6">
                                       <input type="file" name="residual_file" class="form-control">
                                    </div>
                                </div>
                           </fieldset>
                             <div class="form-group">
                                 <div class="col-md-6 col-md-offset-4">
            <!-- <a href="{{ route('addlease.payments.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a> -->
                                     <button type="submit" class="btn btn-success">
                                    Submit
                                    </button>
                                </div>
                            </div>
                        </form>
            </div>
            <!----------end the form when checkbox is checked------------>

        

                       
                </div>
            </div>



        </div>
    </div>
@endsection
@section('footer-script')
 <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script>
        $(function(){
            $("input[name='residual_gurantee']").on('change', function(){
                if($('#residual_gurantee_yes:checked').val() == 'yes'){
                    $("#residual_gurantee_no").prop('checked', false);
                    $('#residualclass').show();
                }else if($('#residual_gurantee_no:checked').val() == 'no'){
                     $("#residual_gurantee_yes").prop('checked', false);
                     $('#residualclass').hide();
                }else {
                    $('#residualclass').hide();
                }
              });
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
@endsection