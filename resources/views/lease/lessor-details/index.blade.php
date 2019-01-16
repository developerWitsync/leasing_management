@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
        <div class="panel panel-default">
            <div class="panel-heading">Add New Lease | Lessor Details</div>

            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif
          
                @if($reporting_currency_settings->is_foreign_transaction_involved == 'yes' || $reporting_currency_settings->is_foreign_transaction_involved == 'no' )
               
                {{--@include('lease._menubar')--}}
               <div class="tab-content" style="padding: 0px;">
                    <div role="tabpanel" class="tab-pane active">
                        @if($lease->id)
                            <form id="add-new-lease-form" class="form-horizontal" method="POST" action="{{ route('add-new-lease.index.update', ['id' => $lease->id]) }}" enctype="multipart/form-data">
                        @else
                            <form id="add-new-lease-form" class="form-horizontal" method="POST" action="{{ route('add-new-lease.index.save') }}" enctype="multipart/form-data">
                        @endif
                            {{ csrf_field() }}

                            <div class="form-group{{ $errors->has('lessor_name') ? ' has-error' : '' }} required">
                                <label for="lessor_name" class="col-lg-4 col-md-5 control-label">Lessor Name</label>
                                <div class="col-lg-4 col-md-6">
                                        <input id="lessor_name" type="text" placeholder="Lessor Name" class="form-control" name="lessor_name" value="{{ old('lessor_name',$lease->lessor_name) }}" >
                                    @if ($errors->has('lessor_name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lessor_name') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group{{ $errors->has('lease_type_id') ? ' has-error' : '' }} required">
                                <label for="lease_type_id" class="col-lg-4 col-md-5 control-label">Lease Type Classification</label>
                                <div class="col-lg-4 col-md-6">
                                   <select name="lease_type_id" class="form-control" id="lease_type_id">
                                        <option value="">Please Type Classification</option>
                                         @php $i =1 @endphp
                                         @foreach($contract_classifications as $classification)
                                         <option class="cla-{{$i}}" value="{{ $classification->id }}" @if(old('lease_type_id',$lease->lease_type_id) == $classification->id) selected="selected" @endif>
                                            {{ $classification->title }} </option>
                                           @php $i++ @endphp
                                        @endforeach
                                    </select>
                                    @if ($errors->has('lease_type_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_type_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            @if($reporting_currency_settings->is_foreign_transaction_involved == "yes")
                                  <div class="form-group{{ $errors->has('lease_contract_id') ? ' has-error' : '' }} required">
                                        <label for="lease_contract_id" class="col-lg-4 col-md-5 control-label">Lease Contract Currency</label>
                                        <div class="col-lg-4 col-md-6">
                                              <select name="lease_contract_id" class="form-control">
                                                <option value="">Please Type Lease Contract</option>

                                                @foreach($reporting_foreign_currency_transaction_settings as $currencies)
                                                 <option value="{{ $currencies->foreign_exchange_currency }}" @if(old('lease_contract_id', $lease->lease_contract_id) == $currencies->foreign_exchange_currency) selected="selected" @endif>
                                                    {{ $currencies->foreign_exchange_currency }}{{ '('.$currencies->base_currency.')' }}</option>
                                                     @endforeach
                                            </select>
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
                                            <label for="lease_contract_id" class="col-lg-4 col-md-5 control-label">Lease Contract Currency</label>
                                            <div class="col-lg-4 col-md-6">
                                                <select name="lease_contract_id" class="form-control">
                                                    <option value="">Please Type Lease Contract</option>
                                                     <option value="{{ $reporting_currency_settings->currency_for_lease_reports }}" @if(old('lease_contract_id', $lease->lease_contract_id) == $reporting_currency_settings->currency_for_lease_reports) selected="selected" @endif >
                                                        {{ $reporting_currency_settings->currency_for_lease_reports }}</option>
                                                </select>
                                                @if ($errors->has('lease_contract_id'))
                                                    <span class="help-block">
                                                        <strong>{{ $errors->first('lease_contract_id') }}</strong>
                                                    </span>
                                                @endif
                                            </div>
                                     </div>
                            @endif
                            <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }} ">
                                <label for="file" class="col-lg-4 col-md-5 control-label">Upload a Copy of Contract Signed</label>
                                <div class="col-lg-4 col-md-6 frmattachFile">
                                    <input type="name" id="upload" name="name" class="form-control" disabled="disabled">
                                    <button type="button" class="browseBtn">Browse</button>
                                    <input type="file" id="file-name" name="file" class="fileType">
                                    @if ($errors->has('file'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('file') }}</strong>
                                        </span>
                                    @endif
                                </div>
                               @if($lease->file !='')
                                  <a href="{{asset('uploads/'.$lease->file)}}" class="downloadIcon" target="_blank"><i class="fa fa-download"></i></a>
                                  @endif
                            </div>

                            <div class="form-group">
                                <div class="col-lg-4 col-md-6 col-lg-offset-4 col-md-offset-5 frmBtnBx">
                                   <a href="/home" class="btn btn-danger">Cancel</a>

                                    <button type="submit" class="btn btn-success">
                                        Save As Draft
                                    </button>

                                    <button type="submit" class="btn btn-primary next_submit">
                                        Next
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                 @else
                 @if(Auth::user()->parent_id==0)
                <a href="{{route('settings.currencies')}}"><div class="alert alert-danger">Please change the foreign currency settings</div></a>
               @endif
             @endif
            @if(Auth::user()->parent_id!=0)
             <div class="alert alert-danger">“Super Admin has not created the settings that can be utilised by you. Please contact to your Super Admin to generate the Settings. Thanks!”</div>
            @endif
            </div>
        </div>
@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script type="text/javascript">
    $(document).ready(function() {
         $("#lease_type_id").on('change', function(){
            var value = $(this).val();
            if(value == '1')
            {
                var modal = bootbox.dialog({
                    message: "Does any Non-Lease component also exist? ",
                    buttons: [
                      {
                        label: "Yes",
                        className: "btn btn-success pull-left",
                        callback: function() {
                            secondPopUp();
                            return true;
                        }
                      },
                      {
                        label: "No",
                        className: "btn btn-danger pull-left",
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
                message: 'If Non-Lease Component Exists, please select Single Lease & Non-Lease Contract.',
                buttons: [
                  {
                    label: "OK",
                    className: "btn btn-success pull-left",
                    callback: function() {
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

        $('.next_submit').on('click', function(e) {
            e.preventDefault();
            var next_url = $('#add-new-lease-form').attr('action')+"?action=next";
            $('#add-new-lease-form').attr('action', next_url);
            // alert(next_url);
            $('#add-new-lease-form').submit();
        });


        $('#file-name').change(function(){
            $('#file-name').show();
            var filename = $('#file-name').val();
            var or_name=filename.split("\\");
            $('#upload').val(or_name[or_name.length-1]);
        });
    });
</script>
@endsection
