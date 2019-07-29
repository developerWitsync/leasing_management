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


                    <form class="form-horizontal" method="POST" action="{{ route('settings.index.incorporationdate') }}" id="confirm_date_of_incorporation">
                        {{ csrf_field() }}
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <span>Date Of Incorporation</span>
                            </div>
                            <div class="setting">
                                <div class="form-group{{ $errors->has('date_of_incorporation') ? ' has-error' : '' }} required">
                                    <label for="date_of_incorporation" class="col-md-4 control-label">Date of Incorporation</label>
                                    <div class="col-md-5">
                                        <div class="from-group">
                                            <input type="text" name="date_of_incorporation" id="date_of_incorporation"
                                                   value="{{ old('date_of_incorporation', $date_of_incorporation) }}"
                                                   @if(!is_null($date_of_incorporation)) disabled="disabled" @endif
                                                   class="form-control">
                                        </div>
                                        @if ($errors->has('date_of_incorporation'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('date_of_incorporation') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if(is_null($date_of_incorporation))
                                    <div class="form-group">
                                        <div class="col-md-6 col-md-offset-4">
                                            <button type="submit" class="btn btn-success confirm_date_of_incorporation">
                                                Save
                                            </button>
                                        </div>
                                    </div>
                                @endif

                            </div>
                        </div>
                    </form>

                    <form class="form-horizontal" method="POST" action="{{ route('settings.index.basedatestandards') }}">
                        {{ csrf_field() }}
                        <div class="panel panel-info">
                            <div class="panel-heading">
                                <span>Base Date Standards</span>
                            </div>
                            <div class="setting">
                                <div class="form-group{{ $errors->has('effective_date_of_standard') ? ' has-error' : '' }} required">
                                    <label for="effective_date_of_standard" class="col-md-4 control-label">Effective
                                        Date of Standard</label>
                                    <div class="col-md-5">
                                        <div class="from-group">
                                            <input type="text" name="effective_date_of_standard"
                                                   value="{{ \Carbon\Carbon::parse($standard_base_date)->format(config('settings.date_format')) }}"
                                                   class="form-control" readonly="readonly">
                                        </div>
                                        @if ($errors->has('effective_date_of_standard'))
                                            <span class="help-block">
                                                <strong>{{ $errors->first('effective_date_of_standard') }}</strong>
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                @if(\Carbon\Carbon::parse($date_of_incorporation)->lessThan($standard_base_date))
                                    <div class="form-group{{ $errors->has('annual_financial_reporting_year_end_date') ? ' has-error' : '' }} required">
                                        <label for="annual_financial_reporting_year_end_date"
                                               class="col-md-4 control-label">Immediate Previous Annual Financial
                                            Reporting Year End Date under Old Leasing Standard</label>
                                        <div class="col-md-5">
                                            <div class="from-group">
                                                <input type="text" name="annual_financial_reporting_year_end_date"
                                                       id="annual_financial_reporting_year_end_date"
                                                       value="{{ old('annual_financial_reporting_year_end_date', $settings->annual_financial_reporting_year_end_date) }}"
                                                       class="form-control" style="background-color: #fff;"
                                                       readonly="readonly">
                                            </div>
                                            @if ($errors->has('annual_financial_reporting_year_end_date'))
                                                <span class="help-block">
                                                <strong>{{ $errors->first('annual_financial_reporting_year_end_date') }}</strong>
                                            </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-success">
                                            Save
                                        </button>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </form>

                    @if(\Carbon\Carbon::parse($date_of_incorporation)->lessThan($standard_base_date) && $show_date_of_initial_application)
                        <form class="form-horizontal" method="POST"
                              action="{{ route('settings.index.saveapplicationstandards') }}"
                              id="save_date_of_initial_application">
                            {{ csrf_field() }}
                            <div class="panel panel-info">
                                <div class="panel-heading">
                                    <span> Date of Initial Application of the New Leasing Standard</span>
                                    @php
                                        $method_title = getParentDetails()->accountingStandard->title;
                                        $base_date = \Carbon\Carbon::parse($calculated_base_date)->format('F d, Y');
                                        $string = "{$method_title} effective for annual periods beginning on or after {$base_date}. In case of Applying Modified Retrospective Valuation Approach, please select effective date {$base_date} while in case of applying Full Retrospective Valuation Approach, please select Prior to {$base_date}."
                                    @endphp
                                    {!! renderToolTip($string,'', 'right') !!}
                                </div>
                                <div class="setting form-group{{ $errors->has('date_of_initial_application') ? ' has-error' : '' }} required">
                                    <div class="col-md-12 rightx">
                                        <div class="input-group col-md-12">
                                            <div class="form-check col-md-4 ">
                                                <input class="form-check-input" type="radio"
                                                       @if(old('date_of_initial_application', isset($settings->date_of_initial_application)?$settings->date_of_initial_application:"") == '1') checked="checked"
                                                       @endif name="date_of_initial_application" value="1"
                                                       id="jan_1_2019"
                                                       @if(isset($settings->is_initial_date_of_application_saved) && $settings->is_initial_date_of_application_saved == 1) disabled="disabled" @endif>
                                                <label class="form-check-label" for="jan_1_2019">
                                                    {{ \Carbon\Carbon::parse($calculated_base_date)->addDay(1)->format('F d, Y') }}
                                                </label>
                                            </div>

                                            <div class="form-check col-md-4">
                                                <input class="form-check-input" type="radio"
                                                       @if(old('date_of_initial_application', isset($settings->date_of_initial_application)?$settings->date_of_initial_application:"") == '2') checked="checked"
                                                       @endif name="date_of_initial_application" value="2"
                                                       id="earlier_jan_1_2019"
                                                       @if(isset($settings->is_initial_date_of_application_saved) && $settings->is_initial_date_of_application_saved == 1) disabled="disabled" @endif>
                                                <label class="form-check-label" for="earlier_jan_1_2019">
                                                    {{ \Carbon\Carbon::parse($calculated_base_date)->addDay(1)->subDay(365)->format('F d, Y') }}
                                                </label>
                                            </div>

                                            @if(!isset($settings->date_of_initial_application) || (isset($settings->date_of_initial_application) && $settings->is_initial_date_of_application_saved  == 0))
                                                <div class="form-group col-md-4">
                                                    <div class="col-md-6 col-md-offset-4">
                                                        <button type="submit"
                                                                class="btn btn-success btn_confirm_submit">
                                                            Confirm
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    </div>

                                    <div class="col-md-12 rightx">
                                        <div class="col-md-4">
                                            <div class="modified_retrospective_approach  @if(old('date_of_initial_application', isset($settings->date_of_initial_application)?$settings->date_of_initial_application:"") == '1')
                                            @else hidden @endif">Modified Retrospective Approach
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="full_retrospective_approach @if(old('date_of_initial_application', isset($settings->date_of_initial_application)?$settings->date_of_initial_application:"") == '2')
                                            @else hidden @endif"
                                            ">Full Retrospective Approach
                                        </div>
                                    </div>
                                </div>

                                @if ($errors->has('date_of_initial_application'))
                                    <span class="help-block">
                                    <strong>{{ $errors->first('date_of_initial_application') }}</strong>
                                </span>
                                @endif
                            </div>
                        </form>
                </div>
                @endif

                <form class="form-horizontal" method="POST" action="{{ route('settings.index.save') }}">
                    {{ csrf_field() }}
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <span> Control Lease Term - Maximum & Minimum</span>
                        </div>
                        <div class="setting">
                            <div class="form-group{{ $errors->has('min_previous_first_lease_start_year') ? ' has-error' : '' }} required">
                                <label for="min_previous_first_lease_start_year" class="col-md-4 control-label">Minimum
                                    Previous First Lease Start Year</label>
                                <div class="col-md-5">
                                    <div class="from-group">
                                        <select name="min_previous_first_lease_start_year"
                                                id="min_previous_first_lease_start_year  " type="text"
                                                placeholder="Select Year"
                                                class="form-control max_previous_lease_start_year">
                                            <option value="">Please select Year</option>
                                            {{ $earliest_year = 1990 }}
                                            @php
                                                $earliest_year = 1990;
                                                if(!is_null($date_of_incorporation)){
                                                    $earliest_year = \Carbon\Carbon::parse($date_of_incorporation)->format('Y');
                                                }
                                            @endphp
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
                                <div class="col-md-1 infoToolTipBox">
                                    {!! renderToolTip('Select minimum previous year from when the existing lease started. You can change later also.', null, 'top') !!}
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('max_lease_end_year') ? ' has-error' : '' }} required">
                                <label for="max_lease_end_year" class="col-md-4 control-label">Maximum Lease End
                                    Year</label>
                                <div class="col-md-5">
                                    <div class="from-group">

                                        <select name="max_lease_end_year" id="max_lease_end_year" type="text"
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
                                <div class="col-md-1 infoToolTipBox">
                                    {!! renderToolTip('Select maximum future year until the existing lease term valid. You can change later also.') !!}
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

                    </div>
                </form>
            </div>

{{--            <div class="panel panel-info">--}}
{{--                <div class="panel-heading">--}}
{{--                    Your Annual Financial Reporting Period--}}
{{--                    {!! renderToolTip('Select your applicable annual financial reporting period.') !!}--}}
{{--                </div>--}}
{{--                <div class="panel-body">--}}
{{--                    <div role="tabpanel" class="tab-pane active">--}}
{{--                        <form class="form-horizontal" method="POST"--}}
{{--                              action="{{ route('settings.index.financialreportingperiod') }}">--}}
{{--                            {{ csrf_field() }}--}}
{{--                            <div class="form-group{{ $errors->has('reporting_period_id') ? ' has-error' : '' }} required">--}}
{{--                                <label for="reporting_period_id" class="col-md-4 control-label">Financial Reporting--}}
{{--                                    Period</label>--}}
{{--                                <div class="col-md-6">--}}
{{--                                    <div class="from-group">--}}

{{--                                        <select name="reporting_period_id" id="reporting_period_id"--}}
{{--                                                placeholder="Select Financial Reporting Period" class="form-control">--}}
{{--                                            <option value="">Please select Reporting Period</option>--}}
{{--                                            @foreach ($reporting_periods as $period)--}}
{{--                                                <option value="{{ $period->id }}"--}}
{{--                                                        @if(old('reporting_period_id', $financial_reporting_period_setting->reporting_period_id) == $period->id) selected="selected" @endif>{{ $period->title }}</option>--}}
{{--                                            @endforeach--}}
{{--                                        </select>--}}
{{--                                    </div>--}}
{{--                                    @if ($errors->has('reporting_period_id'))--}}
{{--                                        <span class="help-block">--}}
{{--                                            <strong>{{ $errors->first('reporting_period_id') }}</strong>--}}
{{--                                        </span>--}}
{{--                                    @endif--}}
{{--                                </div>--}}
{{--                                <div class="form-group">--}}
{{--                                    <div class="col-md-6 col-md-offset-4">--}}
{{--                                        <button type="submit" class="btn btn-success">--}}
{{--                                            Save--}}
{{--                                        </button>--}}
{{--                                    </div>--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </form>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}


            <div class="panel panel-info">
                <div class="panel-heading">
                    Add Countries Where Lease Assets Located
                    {!! renderToolTip('Add countries where lease assets located including country of this company incorporated.') !!}
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
                    Lock Your Lease Valuations
                    {!! renderToolTip('Lock the annual periods as and when the audit get completed. You can unlock later as well.') !!}
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
                            html += "&nbsp;&nbsp;<button  data-toggle='tooltip' data-placement='top' title='Remove Country' type=\"button\" data-setting_id='" + full['id'] + "' class=\"btn btn-sm btn-danger delete_country_setting\">  <i class=\"fa fa-trash-o fa-lg\"></i></button>";
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



            $("#date_of_incorporation").datepicker({
                changeMonth: true,
                changeYear: true,
		yearRange: "-100:+0",
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

            $('#annual_financial_reporting_year_end_date').datepicker({
                changeMonth: true,
                changeYear: true,
                minDate: '{{ \Carbon\Carbon::parse($standard_base_date)->subDay(1)->format('m/d/Y') }}',
                maxDate: '{{ \Carbon\Carbon::parse($standard_base_date)->subDay(1)->addYear(1)->subMonthNoOverflow(1)->lastOfMonth()->format('m/d/Y') }}',
                beforeShowDay: function (date) {
                    // getDate() returns the day [ 0 to 31 ]
                    if (date.getDate() == getLastDayOfYearAndMonth(date.getFullYear(), date.getMonth())) {
                        return [true, ''];
                    }

                    return [false, ''];
                }
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


            $(".confirm_date_of_incorporation").on('click', function (e) {
                e.preventDefault();
                bootbox.confirm({
                    message: 'Are you sure of your selection, once selected will not be reverted.',
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
                            $('#confirm_date_of_incorporation').submit();
                        }
                    }
                });
            });

            $(".btn_confirm_submit").on('click', function (e) {
                e.preventDefault();
                bootbox.confirm({
                    message: 'Are you sure of your selection, once selected will not be reverted.',
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
                            $('#save_date_of_initial_application').submit();
                        }
                    }
                });
            });


            $('input[type="radio"]').on('change', function () {
                if ($(this).val() == '2') {
                    bootbox.confirm({
                        message: 'Are you sure to apply this standard for all your leases existing as on {{ \Carbon\Carbon::parse($calculated_base_date)->addDay(1)->subDay(365)->format("F d, Y") }} by adjusting opening equity using Full Retrospective Method',
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
                                $('.modified_retrospective_approach').addClass('hidden');
                                $('.full_retrospective_approach').removeClass('hidden');
                            } else {
                                $("#earlier_jan_1_2019").prop('checked', false);
                                $("#jan_1_2019").prop('checked', true);
                            }
                        }
                    });
                } else {
                    bootbox.confirm({
                        message: 'Are your sure to apply this standard effective from  {{ \Carbon\Carbon::parse($calculated_base_date)->addDay(1)->format("F d, Y") }} using Modified Retrospective Approach',
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
                                $('.modified_retrospective_approach').removeClass('hidden');
                                $('.full_retrospective_approach').addClass('hidden');
                            } else {
                                $("#earlier_jan_1_2019").prop('checked', true);
                                $("#jan_1_2019").prop('checked', false);
                            }
                        }
                    });
                }
            });
        });

        $(document.body).on("click", ".status-update", function () {

            var year = $(this).data('year');
            var status = $(this).data('status');
            var selected_date = $('#lock_unlock_' + year).val();

            if (status == '1') {
                var message = "Your Valuations up to the selected date will be locked and you will not be allowed to enter any leases for such period";
            } else {
                message = "Your selected year will be unlocked and you will be allowed to enter leases for the unlocked period";
            }

            bootbox.confirm({
                message: message,
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
                    }
                }
            });

        });
    </script>
@endsection
