@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <style>
        td.details-control {
            background: url('{{ asset('assets/plugins/datatables/img/details_open.png') }}') no-repeat center center;
            cursor: pointer;
        }

        tr.shown td.details-control {
            background: url(' {{ asset("assets/plugins/datatables/img/details_close.png") }}') no-repeat center center;
        }
    </style>
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

            @include('settings._menubar')

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
                                            <input class="form-check-input" type="radio"
                                                   @if(old('date_of_initial_application', isset($settings->date_of_initial_application)?$settings->date_of_initial_application:"") == '1') checked="checked"
                                                   @endif name="date_of_initial_application" value="1" id="jan_1_2019"
                                                   checked="checked">
                                            <label class="form-check-label" for="jan_1_2019">
                                                {{ \Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)->format('F d, Y') }}
                                            </label>
                                        </div>
                                        <div class="form-check col-md-6">
                                            <input class="form-check-input" type="radio"
                                                   @if(old('date_of_initial_application', isset($settings->date_of_initial_application)?$settings->date_of_initial_application:"") == '2') checked="checked"
                                                   @endif name="date_of_initial_application" value="2"
                                                   id="earlier_jan_1_2019" disabled="disabled">
                                            <label class="form-check-label" for="earlier_jan_1_2019">
                                                Prior
                                                to {{ \Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)->format('F d, Y') }}
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

                            <div class="form-group{{ $errors->has('date_of_initial_application_earlier_date') ? ' has-error' : '' }} date_of_initial_application_earlier_date required"
                                 style="display: none;">
                                <label for="date_of_initial_application_earlier_date" class="col-md-4 control-label">Specify
                                    Date Earlier to Jan 01, 2019</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input id="date_of_initial_application_earlier_date" type="text"
                                               placeholder="Date Earlier to Jan 01, 2019" class="form-control"
                                               name="date_of_initial_application_earlier_date"
                                               value="{{ old('date_of_initial_application_earlier_date') }}" autofocus>
                                        <div class="btn input-group-addon"
                                             onclick="javascript:$('#date_of_initial_application_earlier_date').focus();">
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
                                    <label for="min_previous_first_lease_start_year" class="col-md-4 control-label">Minimum
                                        Previous First Lease Start Year</label>
                                    <div class="col-md-6">
                                        <div class="from-group">
                                            <select name="min_previous_first_lease_start_year"
                                                    id="min_previous_first_lease_start_year  " type="text"
                                                    placeholder="Select Year"
                                                    class="form-control max_previous_lease_start_year">
                                                <option value="">Please select Year</option>
                                                {{ $earliest_year = 1990 }}
                                                @foreach (range(date('Y') - 1, $earliest_year) as $x)
                                                    <option value="{{ $x }}"
                                                            @if(old('min_previous_first_lease_start_year', $settings->min_previous_first_lease_start_year) == $x) selected="selected" @endif>{{ $x }}</option>
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
                                    <label for="max_lease_end_year" class="col-md-4 control-label">Maximum Lease End
                                        Year</label>
                                    <div class="col-md-6">
                                        <div class="from-group">

                                            <select name="max_lease_end_year" id="max_lease_end_year  " type="text"
                                                    placeholder="Select Year" class="form-control max_lease_end_year">
                                                <option value="">Please select Year</option>
                                                {{ $end_year = date('Y') + 100 }}
                                                @foreach (range(date('Y'), $end_year) as $x)
                                                    <option value="{{ $x }}"
                                                            @if(old('max_lease_end_year', $settings->max_lease_end_year) == $x) selected="selected" @endif>{{ $x }}</option>
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
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                    </form>
                </div>
            </div>

            <div class="panel panel-info">
                <div class="panel-heading">
                    Your Annual Financial Reporting Period
                </div>
                <div class="panel-body">
                    <div role="tabpanel" class="tab-pane active">
                        <form class="form-horizontal" method="POST"
                              action="{{ route('settings.index.financialreportingperiod') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('reporting_period_id') ? ' has-error' : '' }} required">
                                <label for="reporting_period_id" class="col-md-4 control-label">Financial Reporting
                                    Period</label>
                                <div class="col-md-6">
                                    <div class="from-group">

                                        <select name="reporting_period_id" id="reporting_period_id"
                                                placeholder="Select Financial Reporting Period" class="form-control">
                                            <option value="">Please select Reporting Period</option>
                                            @foreach ($reporting_periods as $period)
                                                <option value="{{ $period->id }}"
                                                        @if(old('reporting_period_id', $financial_reporting_period_setting->reporting_period_id) == $period->id) selected="selected" @endif>{{ $period->title }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    @if ($errors->has('reporting_period_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('reporting_period_id') }}</strong>
                                        </span>
                                    @endif
                                </div>
                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-success">
                                            Save
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>


            <div class="panel panel-info">
                <div class="panel-heading">
                    Add Countries Where Lease Assets Located
                    <span>
                    <a href="{{ route('settings.index.addleaseassetcountries') }}"
                       class="btn btn-sm btn-primary pull-right">Add More</a>
                    </span>
                </div>
                <div class="panel-body settingTble">
                    <table class="table table-condensed" id="countries_for_lease_assets">
                        <thead>
                        <tr>
                            <th width="80px">Sr No.</th>
                            <th>Country</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                    </table>
                </div>
            </div>


            <div class="panel panel-info">
                <div class="panel-heading">
                    Manage Your Audit Period & Lock Your Lease Valuations
                    {{--<span>--}}
                    {{--<a href="javascript:void(0);" class="btn btn-sm btn-primary pull-right add_more"--}}
                    {{--data-form="add_more_lease_lock_year">Add More</a>--}}
                    {{--</span>--}}

                </div>
                <div class="panel-body settingTble">
                    <table class="table table-condensed" id="leaselock_table">
                        <thead>
                        <tr>
                            <th width="80px">Sr No.</th>
                            <th>Year</th>
                            <th>Select Date</th>
                            <th>Current Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($lease_lock_year_range as $key => $year)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td class="title">
                                    {{ $year }}
                                </td>
                                <td>
                                    <input type="text" id="lock_unlock_{{$year}}" name="lock_unlock[{{$year}}]"
                                           class="form-control lock_unlock" placeholder="Select Date for lock Period"
                                           readonly='true'
                                           value="{{ isset($lease_lock_year[$year])?$lease_lock_year[$year][0]['start_date']:'' }}">
                                </td>
                                <td style="text-align: center">
                                    @if(isset($lease_lock_year[$year]))
                                        @if($lease_lock_year[$year][0]['status'] == 1)
                                            <span class="badge badge-success">Locked</span>
                                        @else
                                            <span class="badge badge-primary">Unlocked</span>
                                        @endif
                                    @else
                                        Not available
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $disabled_lock = false;
                                        $disabled_unlock = false;
                                    @endphp
                                    @if(isset($lease_lock_year[$year]))
                                        @if($lease_lock_year[$year][0]['status'] == 1)
                                            @php
                                                $disabled_lock = true;
                                            @endphp
                                        @else
                                            @php
                                                $disabled_unlock = false;
                                            @endphp
                                        @endif
                                    @endif
                                    <button type="button" data-year='{{ $year }}' data-status="1"
                                            class="btn btn-success status-update waves-effect"
                                            @if($disabled_lock) disabled="disabled" @endif>Lock
                                    </button>

                                    <button type="button" data-year='{{ $year }}' data-status="0"
                                            class="btn btn-danger status-update waves-effect"
                                            @if($disabled_unlock) disabled="disabled" @endif>Unlock
                                    </button>
                                </td>

                            </tr>
                        @endforeach
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
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script>
        $(function () {

            var table_countries_for_lease_assets = $('#countries_for_lease_assets').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    {"data": "country.name", sortable: false},
                    {"data": "id"}
                ],
                "columnDefs": [
                    {
                        "targets": 2,
                        "data": null,
                        "orderable": false,
                        "className": "text-center",
                        "render": function (data, type, full, meta) {
                            var html = "";
                            html += "&nbsp;&nbsp;<button  data-toggle='tooltip' data-placement='top' title='Remove Country' type=\"button\" data-setting_id='" + full['id'] + "' class=\"btn btn-sm btn-danger delete_country_setting\">  <i class=\"fa fa-trash-o fa-lg\"></i></button>"
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('settings.index.fetchleaseassetcountries') }}",
                bFilter: false, bInfo: false, "bLengthChange": false,

            });

            $(document.body).on('click', '.delete_country_setting', function () {
                var setting_id = $(this).data('setting_id');
                bootbox.confirm({
                    message: "Are you sure that you want to delete this? These changes cannot be reverted.",
                    buttons: {
                        confirm: {
                            label: 'Yes',
                            className: 'btn btn-success'
                        },
                        cancel: {
                            label: 'No',
                            className: 'btn btn-danger'
                        }
                    },
                    callback: function (result) {
                        if (result) {
                            $.ajax({
                                url: "/settings/general/delete-lease-asset-country/" + setting_id,
                                type: 'delete',
                                dataType: 'json',
                                success: function (response) {
                                    if (response['status']) {
                                        window.location.reload();
                                    }
                                }
                            })
                        }
                    }
                });
            });

            $("#annual_year_end_on").datepicker({
                changeMonth: true,
                changeYear: true
            });

            $("#date_of_initial_application_earlier_date").datepicker({
                changeMonth: true,
                changeYear: true,
                maxDate: '12/31/2018'
            });
            $('.max_previous_lease_start_year').datepicker({
                changeYear: true,
                showButtonPanel: true,
                yearRange: "-28:-00",
                dateFormat: 'yy',
                onClose: function (dateText, inst) {
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

            @foreach($lease_lock_year_range as $key => $year)
            $("#lock_unlock_{{$year}}").datepicker({
                changeMonth: true,
                changeYear: true,
                minDate: new Date({{ $year }}, 1 - 1, 1),
                @if(\Carbon\Carbon::today()->format('Y') != $year)
                maxDate: new Date({{$year}}, 12 - 1, 31)
                @else
                maxDate: new Date()
                @endif
            });
            @endforeach
        });

        $(document).ready(function () {
            $('input[type="radio"]').on('change', function () {
                if ($(this).val() == 'earlier_jan_1_2019') {
                    $(".date_of_initial_application_earlier_date").show();
                } else {
                    $(".date_of_initial_application_earlier_date").hide();
                }
            })
        });

        $(document.body).on("click", ".status-update", function () {

            var year = $(this).data('year');
            var status = $(this).data('status');
            var selected_date = $('#lock_unlock_' + year).val();

            $.ajax({
                url: "{{ route('settings.leaselockyear.index') }}",
                data: {
                    status: status,
                    start_date: selected_date
                },
                dataType: 'json',
                success: function (response) {
                    window.location.reload();
                }
            });
        });
    </script>
@endsection
