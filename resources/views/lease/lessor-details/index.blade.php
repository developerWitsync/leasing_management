@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Lessor Details</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @include('lease._subsequent_details')

            @if(($reporting_currency_settings->is_foreign_transaction_involved == 'yes' || $reporting_currency_settings->is_foreign_transaction_involved == 'no') && $general_settings_count > 0)

                {{--@include('lease._menubar')--}}
                <div class="tab-content" style="padding: 0px;">
                    <div role="tabpanel" class="tab-pane active">
                        @if($lease->id)
                            <form id="add-new-lease-form" class="form-horizontal" method="POST"
                                  action="{{ route('add-new-lease.index.update', ['id' => $lease->id]) }}"
                                  enctype="multipart/form-data">
                                @else
                                    <form id="add-new-lease-form" class="form-horizontal" method="POST"
                                          action="{{ route('add-new-lease.index.save') }}"
                                          enctype="multipart/form-data">
                                        @endif
                                        {{ csrf_field() }}
                                        <div class="categoriesOuter leasedetOuter clearfix">
                                            <div class="categoriesHd">Lessor Details</div>
                                            <div class="form-group{{ $errors->has('lessor_name') ? ' has-error' : '' }} required">
                                                <label for="lessor_name" class="col-md-12 control-label" style="display:inline;">
                                                    Lessor Name
                                                </label>
                                                {!! renderToolTip('Input the name of the lessor with whom the lease contract was signed.', 'input_info_tooltip', 'top' , 'display:inline;') !!}
                                                <div class="col-md-12">
                                                    <input id="lessor_name" type="text" placeholder="Lessor Name"
                                                           class="form-control" name="lessor_name"
                                                           value="{{ old('lessor_name',$lease->lessor_name) }}"
                                                           @if($subsequent_modify_required) disabled="disabled" @endif>
                                                    @if($subsequent_modify_required)
                                                        <input type="hidden" name="lessor_name"
                                                               value="{{ old('lessor_name', $lease->lessor_name) }}">
                                                    @endif
                                                    @if ($errors->has('lessor_name'))
                                                        <span class="help-block">
                                                        <strong>{{ $errors->first('lessor_name') }}</strong>
                                                    </span>
                                                    @endif
                                                </div>
                                            </div>
                                            <div class="form-group{{ $errors->has('lease_type_id') ? ' has-error' : '' }} required">
                                                <label for="lease_type_id" class="col-md-12 control-label" style="display:inline;">Lease
                                                    Type Classification</label>
                                                {!! renderToolTip('Lease payments under the lease contract which are directly attributes to the Underlying Lease Asset, such lease payments classified as lease components.<br/><br/>Lease payments which are variable or indirectly associated to an underlying lease asset, such lease payments classified as Non-Lease Components.<br/><br/>Where lease payments cannot be classified into Non-Lease Components, those lease payments are classified as Lease & Non-Lease Components to the extent the lease payments cannot be separated.<br/><br/>Select Single Lease Contract where only Lease Components involved else select Single Lease and Non-Lease Contract where involves Non-Lease Components also.', 'input_info_tooltip', 'left' , 'display:inline;') !!}
                                                <div class="col-md-12">
                                                    <select name="lease_type_id" class="form-control"
                                                            id="lease_type_id">
                                                        <option value="">Select Lease Type Classification</option>
                                                        @php $i =1 @endphp
                                                        @foreach($contract_classifications as $classification)
                                                            @if($subsequent_modify_required)

                                                                @if(in_array($classification->id, [1, 3]) && in_array($lease->lease_type_id, [1,3]))
                                                                    <option class="cla-{{$i}}"
                                                                            value="{{ $classification->id }}"
                                                                            @if(old('lease_type_id',$lease->lease_type_id) == $classification->id) selected="selected" @endif>
                                                                        {{ $classification->title }}
                                                                    </option>
                                                                @elseif(in_array($classification->id, [2, 4]) && in_array($lease->lease_type_id, [2,4]))
                                                                    <option class="cla-{{$i}}"
                                                                            value="{{ $classification->id }}"
                                                                            @if(old('lease_type_id',$lease->lease_type_id) == $classification->id) selected="selected" @endif>
                                                                        {{ $classification->title }}
                                                                    </option>
                                                                @else
                                                                    <option class="cla-{{$i}}"
                                                                            value="{{ $classification->id }}"
                                                                            @if(old('lease_type_id',$lease->lease_type_id) == $classification->id) selected="selected"
                                                                            @endif disabled="disabled">
                                                                        {{ $classification->title }}
                                                                    </option>
                                                                @endif

                                                            @else
                                                                <option class="cla-{{$i}}"
                                                                        value="{{ $classification->id }}"
                                                                        @if(old('lease_type_id',$lease->lease_type_id) == $classification->id) selected="selected" @endif>
                                                                    {{ $classification->title }}
                                                                </option>
                                                            @endif
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


                                            <div class="form-group{{ $errors->has('lease_contract_id') ? ' has-error' : '' }} required">
                                                <label for="lease_contract_id" class="col-md-12 control-label" style="display:inline;">Lease
                                                    Contract Currency</label>
                                                    {!! renderToolTip('Select the relevant currency. If the currency not appearing, please first add the currency under settings to proceed further.', 'input_info_tooltip', 'top' , 'display:inline;') !!}
                                                <div class="col-md-12">
                                                    <select name="lease_contract_id" class="form-control"
                                                            @if($subsequent_modify_required) disabled="disabled" @endif>
                                                        <option value="">Select Lease Contract Currency</option>

                                                        @foreach($contract_currencies as $currency)
                                                            <option value="{{ $currency }}"
                                                                    @if(old('lease_contract_id', $lease->lease_contract_id) == $currency) selected="selected" @endif>
                                                                {{ $currency }}</option>
                                                        @endforeach
                                                    </select>

                                                    @if($subsequent_modify_required)
                                                        <input type="hidden" name="lease_contract_id"
                                                               value="{{$lease->lease_contract_id}}"/>
                                                    @endif

                                                    @if ($errors->has('lease_contract_id'))
                                                        <span class="help-block">
                                                    <strong>{{ $errors->first('lease_contract_id') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                            </div>


                                            <div class="form-group{{ $errors->has('file') ? ' has-error' : '' }} ">
                                                <label for="file" class="col-md-12 control-label" style="display:inline;">Upload a Copy of
                                                    Contract Signed</label>
                                                {!! renderToolTip('You can upload a copy of the lease contract here or can also add later under section “Documents” after successfully submitting this lease details.', 'input_info_tooltip', 'top' , 'display:inline;') !!}
                                                <div class="col-md-12 frmattachFile">
                                                    <input type="name" id="upload" name="name" class="form-control"
                                                           disabled="disabled">
                                                    <button type="button" class="browseBtn">Browse</button>
                                                    <input type="file" id="file-name" name="file" class="fileType">
                                                    <h6 class="disabled">{{ config('settings.file_size_limits.file_validation') }}</h6>
                                                    @if ($errors->has('file'))
                                                        <span class="help-block">
                                            <strong>{{ $errors->first('file') }}</strong>
                                                </span>
                                                    @endif
                                                </div>
                                                @if($lease->file !='')
                                                    <a href="{{asset('uploads/'.$lease->file)}}" class="downloadIcon"
                                                       target="_blank"><i class="fa fa-download"></i></a>
                                                @endif
                                            </div>


                                        </div>


                                        <div class="form-group btnMainBx">
                                            <div class="col-md-4 col-sm-4 btn-backnextBx">

                                                <a href="/home"
                                                   class="btn btn-danger">
                                                    {{ env('CANCEL_LABEL')}}</a>

                                            </div>

                                            <div class="col-md-4 col-sm-4 btnsubmitBx aligncenter">

                                                <button type="submit" class="btn btn-success">
                                                    {{ env('SAVE_LABEL') }} <i class="fa fa-download"></i></button>
                                            </div>

                                            <div class="col-md-4 col-sm-4 btn-backnextBx rightlign ">
                                                <button type="submit" class="btn btn-primary next_submit">
                                                    {{ env('NEXT_LABEL') }} <i class="fa fa-arrow-right"></i>
                                                </button>
                                            </div>

                                        </div>

                                    </form>
                    </div>
                </div>
            @else
                @if(Auth::user()->parent_id==0)
                    @if($general_settings_count == 0)
                        {{--<a href="{{route('settings.index')}}">--}}
                        {{--<div class="alert alert-danger">Please create the general settings from the settings menu as well.</div>--}}
                        {{--</a>--}}
                        @include('lease._complete_your_settings')
                    @else
                        {{--<a href="{{route('settings.currencies')}}">--}}
                        {{--<div class="alert alert-danger">Please change the foreign currency settings</div>--}}
                        {{--</a>--}}
                        @include('lease._complete_your_settings')
                    @endif
                @else
                    {{--<div class="alert alert-danger">Super Admin has not created the settings that can be utilised by--}}
                    {{--you. Please contact to your Super Admin to generate the Settings. Thanks!--}}
                    {{--</div>--}}
                    @include('lease._complete_your_settings')
                @endif
            @endif
        </div>
    </div>
@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#lease_type_id").on('change', function () {
                var value = $(this).val();
                if (value == '1' || value == "2") {
                    var modal = bootbox.dialog({
                        message: "Does any Non-Lease component also exist? ",
                        buttons: [
                            {
                                label: "Yes",
                                className: "btn btn-success pull-left",
                                callback: function () {
                                    secondPopUp();
                                    return true;
                                }
                            },
                            {
                                label: "No",
                                className: "btn btn-danger pull-left",
                                callback: function () {
                                    console.log("just do something on close");
                                }
                            }
                        ],
                        show: false,
                        onEscape: function () {
                            modal.modal("hide");
                        }
                    });

                    modal.modal("show");
                }
            });

            function secondPopUp() {
                var message = 'If Non-Lease Component Exists, please select Single Lease & Non-Lease Contract.';
                if ($('#lease_type_id').val() == '2') {
                    message = 'If Non-Lease Component Exists, please select Mulitple Lease & Non-Lease Contract.';
                }
                var modal = bootbox.dialog({
                    message: message,
                    buttons: [
                        {
                            label: "OK",
                            className: "btn btn-success pull-left",
                            callback: function () {
                            }
                        }
                    ],
                    show: false,
                    onEscape: function () {
                        modal.modal("hide");
                    }
                });

                modal.modal("show");
            }

            $('.next_submit').on('click', function (e) {
                e.preventDefault();
                // alert('dfdsf');
                var next_url = $('#add-new-lease-form').attr('action') + "?action=next";
                $('#add-new-lease-form').attr('action', next_url);
                // alert(next_url);
                $('#add-new-lease-form').submit();
            });


            $('#file-name').change(function () {
                $('#file-name').show();
                var filename = $('#file-name').val();
                var or_name = filename.split("\\");
                $('#upload').val(or_name[or_name.length - 1]);
            });
        });
    </script>
@endsection
