@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
    <style type='text/css'>
  /* Style to hide Dates / Months */
 /* .ui-datepicker-calendar,.ui-datepicker-month { display: none; }​*/
</style>
@endsection
@section('content')
        <div class="panel panel-default">
            {{--<div class="panel-heading">General Settings</div>--}}

            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @include('settings._menubar')

                <div class="">
                    <div role="tabpanel" class="tab-pane active">
                        <form class="form-horizontal" method="POST" action="{{ route('settings.index.save') }}">
                            {{ csrf_field() }}
                          
                           <div class="panel panel-info">
                            <div class="panel-heading">
                              <span> Date of Initial Application of the New Leasing Standard</span>
                          </div>
                            <div class="form-group{{ $errors->has('date_of_initial_application') ? ' has-error' : '' }} required">
                               
                                <div class="col-md-12 rightx">
                                    <div class="input-group col-md-12">
                                        <div class="form-check col-md-4 ">
                                            <input class="form-check-input" type="radio" @if(old('date_of_initial_application', isset($settings->date_of_initial_application)?$settings->date_of_initial_application:"") == '1') checked="checked" @endif name="date_of_initial_application" value="1" id="jan_1_2019">
                                            <label class="form-check-label" for="jan_1_2019">
                                                January 01, 2019
                                            </label>
                                        </div>
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" type="radio" @if(old('date_of_initial_application', isset($settings->date_of_initial_application)?$settings->date_of_initial_application:"") == '2') checked="checked" @endif name="date_of_initial_application" value="2" id="earlier_jan_1_2019" disabled="disabled">
                                            <label class="form-check-label" for="earlier_jan_1_2019">
                                                Prior to January 01, 2019
                                            </label>
                                        </div>
                                    </div>
                                    @if ($errors->has('date_of_initial_application'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('date_of_initial_application') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('date_of_initial_application_earlier_date') ? ' has-error' : '' }} date_of_initial_application_earlier_date required" style="display: none;">
                                <label for="date_of_initial_application_earlier_date" class="col-md-4 control-label">Specify Date Earlier to Jan 01, 2019</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input id="date_of_initial_application_earlier_date" type="text" placeholder="Date Earlier to Jan 01, 2019" class="form-control" name="date_of_initial_application_earlier_date" value="{{ old('date_of_initial_application_earlier_date') }}" autofocus>
                                        <div class="btn input-group-addon" onclick="javascript:$('#date_of_initial_application_earlier_date').focus();">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                    @if ($errors->has('date_of_initial_application_earlier_date'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('date_of_initial_application_earlier_date') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    <div class="panel panel-info">
                            <div class="panel-heading">
                              <span> Control Lease Term - Maximum & Minimum</span>
                          </div>
                          <div class="setting">
                             <div class="form-group{{ $errors->has('min_previous_first_lease_start_year') ? ' has-error' : '' }} required">
                                <label for="min_previous_first_lease_start_year" class="col-md-4 control-label">Minimum Previous First Lease Start Year</label>
                                <div class="col-md-6">
                                    <div class="from-group">

                                        <select name="min_previous_first_lease_start_year" id="min_previous_first_lease_start_year  " type="text" placeholder="Select Year" class="form-control max_previous_lease_start_year">
                                        <option value="">Please select Year</option>
                                         {{ $earliest_year = 1990 }}
                                         @foreach (range(date('Y') - 1, $earliest_year) as $x)
                                         <option value="{{ $x }}" @if(old('min_previous_first_lease_start_year', $settings->min_previous_first_lease_start_year) == $x) selected="selected" @endif>{{ $x }}</option>
                                        @endforeach
                                        </select>
                                      </div>
                                    @if ($errors->has('min_previous_first_lease_start_year'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('min_previous_first_lease_start_year') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('max_lease_end_year') ? ' has-error' : '' }} required">
                                <label for="max_lease_end_year" class="col-md-4 control-label">Maximum Lease End Year</label>
                                <div class="col-md-6">
                                    <div class="from-group">

                                        <select name="max_lease_end_year" id="max_lease_end_year  " type="text" placeholder="Select Year" class="form-control max_lease_end_year">
                                            <option value="">Please select Year</option>
                                            {{ $end_year = date('Y') + 100 }}
                                            @foreach (range(date('Y'), $end_year) as $x)
                                                <option value="{{ $x }}" @if(old('max_lease_end_year', $settings->max_lease_end_year) == $x) selected="selected" @endif>{{ $x }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('max_lease_end_year'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('max_lease_end_year') }}</strong>
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
                        </div>
                            </form>
                        </div>
                            </div>
                            <div class="panel panel-info">
                            <div class="panel-heading">
                                Manage Your Audit Period & Lock Your Lease Valuations
                                <span>
                                    <a href="javascript:void(0);" class="btn btn-sm btn-primary pull-right add_more" data-form="add_more_lease_lock_year">Add More</a>
                                </span>
                             
                            </div>
                 <div class="panel-body settingTble">
                    <table class="table table-condensed" id="leaselock_table">
                     <thead>
                        <tr>
                            <th width="80px">Sr No.</th>
                            <th>Adult Year ended on</th>
                            <th width="120px">Status</th>

                            <th width="120px">Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lease_lock_year as $key => $value)
                        <tr>
                         <td>{{ $key + 1 }}</td>
                          <td class="title">
                                    {{ date('d-m-Y', strtotime($value->start_date)) }}
                          </td>
                          <td>
                            @if($value->status ==0)
                            <button type="button" data-id='{{ $value->id}}'  data-status="1" class="btn btn-success status-update waves-effect">Lock</button>
                            @else
                             <button type="button" data-id='{{ $value->id}}'  data-status="0" class="btn btn-danger status-update waves-effect">Unlock</button>
                             @endif
                          </td>
                          <td>
                            <a data-href="{{ route('settings.leaselockyear.editleaselockyear', ['id' => $value->id]) }}" href="javascript:;" class="btn btn-sm btn-success edit_table_setting"><i class="fa fa-pencil-square-o"></i></a>
 <a data-href="{{ route('settings.leaselockyear.deleteleaselockyear', ['id' => $value->id]) }}" href="javascript:;" class="btn btn-sm btn-danger delete_settings"><i class="fa fa-trash-o"></i></a>
                        </td>
                                        </tr>
                                    @endforeach
                                    <tr style=" {{ $errors->has('lease_lock_year') ? ' has-error' : 'display: none' }}" class="add_more_lease_lock_year">
                                        <td>{{ count($lease_lock_year) + 1 }}</td>
                                        <td>
                                        <form action="{{ route('settings.leaselockyear.addleaselockyear') }}" method="POST" class="add_more_lease_lock_year_form"> {{ csrf_field() }}
                                        <div class="form-group{{ $errors->has('start_date') ? ' has-error' : '' }}">
                                        <input type="text" value="{{ old('start_date') }}" name="start_date" placeholder="Select Date" class="form-control {{ $errors->has('start_date') ? ' has-error' : '' }}"/ id="start_date"> 
                                        @if ($errors->has('start_date'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('start_date') }}</strong>
                                        </span>
                                                    @endif
                                                </div>
                                            </form>
                                        </td>
                                        <td>
                                            <button type="button" onclick="javascript:$('.add_more_lease_lock_year_form').submit();" class="btn btn-sm btn-success">Save</button>
                                            <a href="javascript:;" class="btn btn-sm btn-danger add_more" data-form="add_more_lease_lock_year">Cancel</a>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

        <div id="myModal" class="modal fade" role="dialog">
            <div class="modal-dialog">
                <!-- Modal content-->
                <div class="modal-content">

                </div>
            </div>
        </div>
@endsection
@section('footer-script')
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script src="{{ asset('js/jquery-ui.js') }}"></script>
    <script>
        $(function() {
            $("#annual_year_end_on").datepicker({
                changeMonth: true,
                changeYear: true
            });

            $("#date_of_initial_application_earlier_date").datepicker({
                changeMonth: true,
                changeYear: true,
                maxDate : '12/31/2018'
            });
             $('.max_previous_lease_start_year').datepicker({
                    changeYear: true,
                    showButtonPanel: true,
                    yearRange: "-28:-00",
                    dateFormat: 'yy',
                    onClose: function(dateText, inst) { 
                var year = $("#ui-datepicker-div .ui-datepicker-year :selected").val();
                  $(this).datepicker('setDate', new Date(year, 1));
            }
        });
            $(".max_previous_lease_start_year").focus(function () {
               $(".ui-datepicker-month").hide();
            });

            $("#start_date").datepicker({
                changeMonth: true,
                changeYear: true
            });

            $("#end_date").datepicker({
                changeMonth: true,
                changeYear: true
            });
        });

        $(document).ready(function () {
            $('input[type="radio"]').on('change', function () {
                if($(this).val() == 'earlier_jan_1_2019') {
                    $(".date_of_initial_application_earlier_date").show();
                } else {
                    $(".date_of_initial_application_earlier_date").hide();
                }
            })
        });

         $(document.body).on("click", ".status-update", function () {

            var id = $(this).data('id');
            var  status = $(this).data('status');
            
                $.ajax({
                    url : "{{ route('settings.leaselockyear.changestatus') }}",
                    data : {
                        id : id,
                        status : status
                    },

                    dataType : 'json',
                    success : function (response) {
                        window.location.reload();
                    }
                });
            });
    </script>
@endsection
