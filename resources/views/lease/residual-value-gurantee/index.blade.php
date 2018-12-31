@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Add New Lease | Add Residual Value Gurantee</div>

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
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                             <th>Name of the Underlying Lease Asset</th>
                            <th>Underlying Leased Asset Classification</th>
                            <th>Any Residual Guarantee Value</th>
                           <!--  <th>Nature Of Lease Payement</th>
                            <th>Action</th> -->
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
                                    {{ $asset->name }}
                                </td>
                                <td>
                                    {{ $asset->subcategory->title }}
                                </td>
                                <td>
                                    <input class="form-check-input" name="residual_gurantee" type="checkbox" id="residual_gurantee_yes" value="yes"><label clas="form-check-label" for="residual_gurantee_yes" style="vertical-align: 4px">Yes</label>
                        
                                    <input class="form-check-input" name="residual_gurantee" type="checkbox" id="residual_gurantee_no" value="no">
                                    <label class="form-check-label" for="residual_gurantee_no" style="vertical-align: 4px">No</label>
                 
                                </td>
                               <!--  <td>
                                
                        <select name="nature_of_lease" class="form-control asset_category" data-number="">
                            <option value="">--Select--</option>
                            @foreach($lease_payments_nature as $lease_payement)
                                <option value="{{ $lease_payement->id }}">{{ $lease_payement->title }}</option>
                           @endforeach
                        </select>
                   
                                </td> 
                                <td>
                                    &nbsp;<a class="btn btn-sm btn-info" href="">Upload</a>
                                </td>-->
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <!---start the for when checkbox is checked---------->
            <div id="residualclass" style="display:none;">
                  <form role="form" class="form-horizontal">

                            <fieldset class="scheduler-border">
                                <legend class="scheduler-border">If Yes</legend>

                                <div class="form-group{{ $errors->has('nature') ? ' has-error' : '' }} required">
                                    <label for="type" class="col-md-4 control-label">Nature of Lease Payment</label>
                                    <div class="col-md-6">
                                        <select name="nature" id="nature" class="form-control">
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
                                        <input class="form-check-input" name="amount_determinable" type="checkbox" id="amount_determinable_yes" value="yes"><label clas="form-check-label" for="amount_determinable_yes_yes" style="vertical-align: 4px">Yes</label>
                        
                                    <input class="form-check-input" name="amount_determinable" type="checkbox" id="amount_determinable_no" value="no">
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

                               

                            </fieldset>
                        </form>
            </div>
            <!----------end the form when checkbox is checked------------>
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
              $("#nature").on('change', function(){
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