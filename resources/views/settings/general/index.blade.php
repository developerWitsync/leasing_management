@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
        <div class="panel panel-default">
            <div class="panel-heading">Settings | General Settings</div>

            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @include('settings._menubar')

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active">
                        <form class="form-horizontal" method="POST" action="{{ route('settings.index.save') }}">
                            {{ csrf_field() }}
                            <div class="form-group{{ $errors->has('annual_year_end_on') ? ' has-error' : '' }} required">
                                <label for="annual_year_end_on" class="col-md-4 control-label">Annual Reporting Period</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <input id="annual_year_end_on" type="text" placeholder="Annual Year End On" class="form-control" name="annual_year_end_on" value="{{ old('annual_year_end_on', isset($settings->annual_year_end_on)?date('m/d/Y', strtotime($settings->annual_year_end_on)):"") }}" autofocus>
                                        <div class="btn input-group-addon" onclick="javascript:$('#annual_year_end_on').focus();">
                                            <i class="fa fa-calendar"></i>
                                        </div>
                                    </div>
                                    @if ($errors->has('annual_year_end_on'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('annual_year_end_on') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('date_of_initial_application') ? ' has-error' : '' }} required">
                                <label for="annual_year_end_on" class="col-md-4 control-label">Date of Initial Application of the New Leasing Standard</label>
                                <div class="col-md-6">
                                    <div class="input-group">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" @if(old('date_of_initial_application', isset($settings->date_of_initial_application)?$settings->date_of_initial_application:"") == '1') checked="checked" @endif name="date_of_initial_application" value="1" id="jan_1_2019">
                                            <label class="form-check-label" for="jan_1_2019">
                                                01/01/2019
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" @if(old('date_of_initial_application', isset($settings->date_of_initial_application)?$settings->date_of_initial_application:"") == '2') checked="checked" @endif name="date_of_initial_application" value="2" id="earlier_jan_1_2019" disabled="disabled">
                                            <label class="form-check-label" for="earlier_jan_1_2019">
                                                Earlier to Jan 01, 2019
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
@endsection
@section('footer-script')
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
        });

        $(document).ready(function () {
            $('input[type="radio"]').on('change', function () {
                if($(this).val() == 'earlier_jan_1_2019') {
                    $(".date_of_initial_application_earlier_date").show();
                } else {
                    $(".date_of_initial_application_earlier_date").hide();
                }
            })
        })
    </script>
@endsection
