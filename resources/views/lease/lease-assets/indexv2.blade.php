@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading clearfix">Create Lease Asset</div>
        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @include('lease._subsequent_details')

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                    <form class="form-horizontal" method="POST"
                          action="{{ route('addlease.leaseasset.saveasset', ['id' => $lease->id]) }}" id="lease_asset">
                        {{ csrf_field() }}

                        <div class="categoriesOuter clearfix">
                        <!--  <div class="categoriesHd">Lease Asset Categorization</div>
                            <div class="form-group{{ $errors->has('uuid') ? ' has-error' : '' }} required">
                                <label for="uuid" class="col-md-12 control-label">ULA CODE</label>
                                <div class="col-md-12">
                                   
                                    <input id="uuid" type="text" placeholder="ULA Code" class="form-control" name="uuid"
                                           value="{{ old('ula_code', $ulacode) }}" readonly="readonly">
                                    @if ($errors->has('uuid'))
                            <span class="help-block">
                            <strong>{{ $errors->first('uuid') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div> -->

                            <div class="form-group{{ $errors->has('category_id') ? ' has-error' : '' }} required">
                                <label for="category_id" class="col-md-12 control-label">Lease Asset Category</label>
                                <div class="col-md-12">
                                    <select name="category_id" class="form-control asset_category"
                                            @if($subsequent_modify_required) disabled="disabled" @endif>
                                        <option value="">--Select--</option>
                                        @foreach($lease_assets_categories as $category)
                                            <option value="{{ $category->id }}"
                                                    @if(old('category_id', $asset->category_id) == $category->id) selected="selected" @endif>{{ $category->title }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('category_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('category_id') }}</strong>
                                        </span>
                                    @endif

                                    @if($subsequent_modify_required)
                                        <input type="hidden" name="category_id" value="{{ $asset->category_id }}"/>
                                    @endif

                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('sub_category_id') ? ' has-error' : '' }} required">
                                <label for="sub_category_id" class="col-md-12 control-label">Lease Asset
                                    Classification</label>
                                <div class="col-md-12">

                                    <select name="sub_category_id" class="form-control sub_category_id"
                                            @if($subsequent_modify_required) disabled="disabled" @endif>
                                        <option value="">--Select--</option>
                                        @if(old('category_id', $asset->category_id))
                                            @foreach($lease_assets_categories as $category)
                                                @if($category->id == old('category_id', $asset->category_id))
                                                    @foreach($category->subcategories as $subcategory)
                                                        <option value="{{ $subcategory->id }}"
                                                                @if($subcategory->id == old('sub_category_id', $asset->sub_category_id)) selected="selected" @endif>{{ $subcategory->title }}</option>
                                                    @endforeach
                                                @endif
                                            @endforeach
                                        @endif
                                    </select>

                                    @if ($errors->has('sub_category_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('sub_category_id') }}</strong>
                                        </span>
                                    @endif

                                    @if($subsequent_modify_required)
                                        <input type="hidden" name="sub_category_id"
                                               value="{{ $asset->sub_category_id }}"/>
                                    @endif

                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }} required">
                                <label for="name" class="col-md-12 control-label">Lease Asset Name</label>
                                <div class="col-md-12">
                                    <input id="name" type="text" placeholder="Asset Name" class="form-control"
                                           name="name" value="{{ old('name', $asset->name) }}"
                                           @if($subsequent_modify_required) disabled="disabled" @endif>
                                    @if ($errors->has('name'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('name') }}</strong>
                                        </span>
                                    @endif

                                    @if($subsequent_modify_required)
                                        <input type="hidden" name="name" value="{{$asset->name}}">
                                    @endif

                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('similar_asset_items') ? ' has-error' : '' }} required">
                                <label for="similar_asset_items" class="col-md-12 control-label">Number of Units of
                                    Similar Characteristics</label>
                                <div class="col-md-12">

                                    <select name="similar_asset_items" class="form-control"
                                            @if($subsequent_modify_required) disabled="disabled" @endif>
                                        <option value="">--Select--</option>
                                        @foreach($la_similar_charac_number as $number)
                                            <option value="{{ $number->number }}"
                                                    @if($number->number == $asset->similar_asset_items) selected="selected" @endif>{{ $number->number }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('similar_asset_items'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('similar_asset_items') }}</strong>
                                        </span>
                                    @endif

                                    @if($subsequent_modify_required)
                                        <input type="hidden" name="similar_asset_items"
                                               value="{{ $asset->similar_asset_items }}"/>
                                    @endif

                                </div>
                            </div>

                        </div>

                        <div class="categoriesOuter clearfix">
                            <div class="categoriesHd">Basic Details of the Underlying Lease Asset</div>
                            <div class="form-group{{ $errors->has('other_details') ? ' has-error' : '' }} ">
                                <label for="other_details" class="col-md-12 control-label">Any other Details of the
                                    Underlying Lease Asset</label>
                                <div class="col-md-12">
                                    <input id="other_details" type="text" placeholder="Other Details"
                                           class="form-control" name="other_details"
                                           value="{{ old('other_details', $asset->other_details) }}">
                                    @if ($errors->has('other_details'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('other_details') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="categoriesOuter clearfix">
                            <div class="categoriesHd">Location of the Underlying Lease Asset</div>
                            <div class="form-group{{ $errors->has('country_id') ? ' has-error' : '' }} required">
                                <label for="country" class="col-md-12 control-label">Country</label>
                                <div class="col-md-12">
                                    <select name="country_id" class="form-control">
                                        <option value="">--Select Country--</option>
                                        @foreach($countries as $country)
                                            <option value="{{ $country->country_id }}"
                                                    @if(old('country_id', $asset->country_id) == $country->country_id) selected="selected" @endif>{{ $country->country->name }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('country_id'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('country_id') }}</strong>
                                        </span>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('location') ? ' has-error' : '' }} required">
                                <label for="location" class="col-md-12 control-label">Place Where Located</label>
                                <div class="col-md-12">
                                    <input id="location" type="text" placeholder="Place Where Located"
                                           class="form-control" name="location"
                                           value="{{ old('location', $asset->location) }}">
                                    @if ($errors->has('location'))
                                        <span class="help-block">
                                        <strong>{{ $errors->first('location') }}</strong>
                                    </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="categoriesOuter clearfix">
                            <div class="categoriesHd">Purpose of the Underlying Lease Asset</div>
                            <div class="form-group{{ $errors->has('specific_use') ? ' has-error' : '' }} required">
                                <label for="specific_use" class="col-md-12 control-label">Specific Use of the Lease
                                    Asset</label>
                                <div class="col-md-12">
                                    <select name="specific_use" class="form-control">
                                        <option value="">--Select Use Of Lease Asset--</option>
                                        @foreach($use_of_lease_asset as $use)
                                            <option value="{{ $use->id }}"
                                                    @if(old('specific_use', $asset->specific_use) == $use->id) selected="selected" @endif>{{ $use->title }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('specific_use'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('specific_use') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>


                            <div class="form-group{{ $errors->has('use_of_asset') ? ' has-error' : '' }} required use_of_asset"
                                 @if(old('specific_use', $asset->specific_use) == '1') style="display: block"
                                 @else style="display: none" @endif>
                                <label for="use_of_asset" class="col-md-12 control-label">State Use Of Asset</label>
                                <div class="col-md-12">
                                    <input id="use_of_asset" type="text" placeholder="State Use Of Asset"
                                           class="form-control" name="use_of_asset"
                                           value="{{ old('use_of_asset', $asset->use_of_asset) }}">
                                    @if ($errors->has('use_of_asset'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('use_of_asset') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="categoriesOuter clearfix">
                            <div class="categoriesHd">Expected Useful Life of the Underlying Lease Asset</div>
                            <div class="form-group{{ $errors->has('expected_life') ? ' has-error' : '' }} required">
                                <label for="expected_life" class="col-md-12 control-label">

                                    Useful Life of the Lease
                                    Asset
                                </label>
                                <div class="col-md-12">
                                    <select name="expected_life" class="form-control">
                                        <option value="">--Expected Life Of Lease Asset--</option>
                                        @foreach($expected_life_of_assets as $life)
                                            {{--@if($asset->category_id && $asset->category_id != '1' && $life->years < 0)--}}
                                                {{--@php--}}
                                                    {{--continue;--}}
                                                {{--@endphp--}}
                                            {{--@endif--}}
                                            <option value="{{ $life->id }}"
                                                    @if(old('expected_life', $asset->expected_life) == $life->id) selected="selected" @endif>{{ ($life->years > 0)?$life->years:'Indefinite' }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('expected_life'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('expected_life') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="categoriesOuter clearfix" id="leaseterm">
                            <div class="categoriesHd">Lease Term of the Underlying Lease Asset</div>
                            <div class="form-group{{ $errors->has('lease_start_date') ? ' has-error' : '' }} required">
                                <label for="lease_start_date" class="col-md-12 control-label">Lease Start Date</label>
                                <div class="col-md-12">
                                    <input id="lease_start_date" type="text" placeholder="Lease Start Date"
                                           class="form-control lease_period" name="lease_start_date"
                                           value="{{ old('lease_start_date', ($asset->lease_start_date)?(\Carbon\Carbon::parse($asset->lease_start_date)->format(config('settings.date_format'))):'') }}"
                                           autocomplete="off"
                                           @if($subsequent_modify_required) disabled="disabled" @endif>
                                    @if ($errors->has('lease_start_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_start_date') }}</strong>
                                        </span>
                                    @endif

                                    @if($subsequent_modify_required)
                                        <input type="hidden" name="lease_start_date"
                                               value="{{ \Carbon\Carbon::parse($asset->lease_start_date)->format(config('settings.date_format')) }}"/>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('lease_free_period') ? ' has-error' : '' }} required">
                                <label for="lease_free_period" class="col-md-12 control-label">Initial Lease Free
                                    Period, If any(In Days)</label>
                                <div class="col-md-12">
                                    <input id="lease_free_period" type="text" placeholder="Number of Days"
                                           class="form-control" name="lease_free_period"
                                           value="{{ old('lease_free_period', $asset->lease_free_period) }}"
                                           @if($subsequent_modify_required) disabled="disabled" @endif>
                                    @if ($errors->has('lease_free_period'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_free_period') }}</strong>
                                        </span>
                                    @endif
                                    @if($subsequent_modify_required)
                                        <input type="hidden" name="lease_free_period"
                                               value="{{ $asset->lease_free_period }}"/>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('accural_period') ? ' has-error' : '' }} required">
                                <label for="accural_period" class="col-md-12 control-label">Start Date of Lease Payment
                                    / Accrual Period</label>
                                <div class="col-md-12">
                                    <input id="accural_period" type="text"
                                           placeholder="Start Date of Lease Payment / Accrual Period"
                                           class="form-control" name="accural_period"
                                           value="{{ old('accural_period',($asset->accural_period)?(\Carbon\Carbon::parse($asset->accural_period)->format(config('settings.date_format'))):'') }}"
                                           readonly="readonly" style="pointer-events: none"
                                           @if($subsequent_modify_required) disabled="disabled" @endif>
                                    @if ($errors->has('accural_period'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('accural_period') }}</strong>
                                        </span>
                                    @endif

                                    @if($subsequent_modify_required)
                                        <input type="hidden" name="accural_period"
                                               value="{{ \Carbon\Carbon::parse($asset->accural_period)->format(config('settings.date_format')) }}"/>
                                    @endif

                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('lease_end_date') ? ' has-error' : '' }} required">
                                <label for="lease_end_date" class="col-md-12 control-label">Lease End Date,
                                    Non-Cancellable Period</label>
                                <div class="col-md-12">
                                    <input id="lease_end_date" type="text"
                                           placeholder="Lease End Date, Non-Cancellable Period"
                                           class="form-control lease_period"
                                           name="lease_end_date"
                                           value="{{ old('lease_end_date', ($asset->lease_end_date)?(\Carbon\Carbon::parse($asset->lease_end_date)->format('d-M-Y')):'') }}"
                                           autocomplete="off">
                                    @if ($errors->has('lease_end_date'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_end_date') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <div class="form-group{{ $errors->has('lease_term') ? ' has-error' : '' }} required">
                                <label for="lease_term" class="col-md-12 control-label">Lease Term (in Months &
                                    Years)</label>
                                <div class="col-md-12">
                                    <input id="lease_term" type="text" placeholder="Lease Term (in Months & Years)"
                                           class="form-control" name="lease_term"
                                           value="{{ old('lease_term', $asset->lease_term) }}" readonly="readonly">
                                    @if ($errors->has('lease_term'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('lease_term') }}</strong>
                                        </span>
                                    @endif
                                </div>
                            </div>

                        </div>

                        <div class="categoriesOuter clearfix" id="prior_accounting" style="display: none">
                            <div class="categoriesHd">Lease Asset Accounting Adopted Prior to 2019</div>
                            <div class="form-group{{ $errors->has('accounting_treatment') ? ' has-error' : '' }} required">
                                <label for="accounting_treatment" class="col-md-12 control-label">Lease Asset Accounting
                                    Treatment Followed Upto 2018</label>
                                <div class="col-md-12">
                                    <select name="accounting_treatment" class="form-control" id="accounting_treatment"
                                            @if($subsequent_modify_required) disabled="disabled" @endif>
                                        <option value="">--Lease Accounting Treatment--</option>
                                        @foreach($accounting_terms as $accounting_term)
                                            <option value="{{ $accounting_term['id']}}"
                                                    @if(old('accounting_treatment', $asset->accounting_treatment) == $accounting_term['id']) selected="selected" @endif>{{ $accounting_term['title'] }}</option>
                                        @endforeach
                                    </select>

                                    @if ($errors->has('accounting_treatment'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('accounting_treatment') }}</strong>
                                        </span>
                                    @endif


                                    @if($subsequent_modify_required)
                                        <input type="hidden" name="accounting_treatment"
                                               value="{{ $asset->accounting_treatment }}"/>
                                    @endif

                                </div>
                            </div>

                        </div>

                        <div class="categoriesOuter using_lease_payment clearfix" style="display:none;">
                            <div class="categoriesHd">Lease Payment Basis</div>
                            <div class="form-group{{ $errors->has('using_lease_payment') ? ' has-error' : '' }} required using_lease_payment"
                                 style="display: block">
                                <label for="variable_amount_determinable" class="col-md-12 control-label">Using Lease
                                    Payment</label>
                                <div class="col-md-12">

                                    <div class="col-md-12 form-check form-check-inline">
                                        <input class="form-check-input" name="using_lease_payment" type="checkbox"
                                               id="yes" value="1"
                                               @if(old('using_lease_payment' ,$asset->using_lease_payment) == "1") checked="checked" @endif @if($subsequent_modify_required) disabled="disabled" @endif>
                                        <label for="yes" class="form-check-label" for="1" style="vertical-align: 2px">Current
                                            Lease Payment as on {{ \Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)->format('F d, Y') }}</label>
                                    </div>

                                    <div class=" col-md-12 form-check form-check-inline">
                                        <input class="form-check-input" name="using_lease_payment" type="checkbox"
                                               id="no" value="2"
                                               @if(old('using_lease_payment',$asset->using_lease_payment) == "2") checked="checked" @endif @if($subsequent_modify_required) disabled="disabled" @endif>
                                        <label for="no" class="form-check-label" for="2" style="vertical-align: 2px">Initial
                                            Lease Payment as on First Lease Start</label>
                                    </div>
                                    @if ($errors->has('using_lease_payment'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('using_lease_payment') }}</strong>
                                        </span>
                                    @endif
                                    @if($subsequent_modify_required)
                                        <input type="hidden" name="using_lease_payment"
                                               value="{{ $asset->using_lease_payment }}"/>
                                    @endif
                                </div>

                            </div>

                        </div>


                        <div class="form-group btnMainBx">
                            <div class="col-md-4 col-sm-4 btn-backnextBx">

                                <a href="{{ route('add-new-lease.index',['id' => $lease->id]) }}"
                                   class="btn btn-danger">
                                    <i class="fa fa-arrow-left"></i> {{ env('BACK_LABEL')}}</a>

                            </div>
                            <div class="col-md-4 col-sm-4 btnsubmitBx aligncenter">

                                <button type="submit" class="btn btn-success">
                                    {{ env('SAVE_LABEL') }} <i class="fa fa-download"></i></button>
                            </div>
                            <div class="col-md-4 col-sm-4 btn-backnextBx rightlign ">
                                <input type="hidden" name="action" value="">
                                <a href="javascript:void(0);" class="btn btn-primary save_next"> {{ env('NEXT_LABEL') }}
                                    <i class="fa fa-arrow-right"></i></a>
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
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script>
        $(document).ready(function () {

            $('.asset_category').on('change', function () {
                $.ajax({
                    url: '/lease/underlying-lease-assets/fetch-sub-categories/' + $(this).val(),
                    type: 'get',
                    dataType: 'json',
                    success: function (response) {
                        $(".sub_category_id").html(response['html']);
                    }
                })
            });

            $('select[name="specific_use"]').on('change', function () {
                if ($(this).val() == '1') {
                    $('.use_of_asset').show();
                } else {
                    $('.use_of_asset').hide();
                }
            });

            $(function () {

                $("input[type='checkbox']").on('click', function () {
                    var group = "input[name='" + $(this).attr("name") + "']";
                    $(group).prop("checked", false);
                    $(this).prop("checked", true);
                });


                function toggleUsinLeasePayment() {
                    var _start_date = $('#accural_period').datepicker('getDate');

                    if (_start_date < new Date('{{ \Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)->format("F d Y") }}')) {
                        $('.using_lease_payment').show();

                        $('#prior_accounting').show();

                    } else {
                        $('.using_lease_payment').hide();

                        $('#prior_accounting').hide();
                        $('#accounting_treatment').val('');
                    }


                }

                $('input[name="using_lease_payment"]').on('click', function () {
                    if ($(this).is(":checked") && $(this).val() == '1') {
                        var message = "You are required to place escalation rates if applicable, effective from 2019.";
                    } else {
                        var message = "You are required to place escalation rates if applicable, effective from the Lease Start Date.";
                    }

                    bootbox.alert(message);
                });


                $("#lease_start_date").datepicker({
                    dateFormat: "dd-M-yy",
                    changeYear: true,
                    changeMonth: true,
                    {!!  getYearRanage() !!}
                    onSelect: function (date, instance) {

                        var _ajax_url = '{{route("lease.checklockperioddate")}}';
                        checklockperioddate(date, instance, _ajax_url);

                        var dt2 = $('#lease_end_date');
                        var startDate = $(this).datepicker('getDate');
                        //add 30 days to selected date
                        startDate.setDate(startDate.getDate() + 30);
                        var minDate = $(this).datepicker('getDate');
                        var dt2Date = dt2.datepicker('getDate');
                        //difference in days. 86400 seconds in day, 1000 ms in second
                        var dateDiff = (dt2Date - minDate) / (86400 * 1000);

                        // dt2.datepicker('option', 'minDate', minDate);

                        setTimeout(function () {
                            resetAllDates();
                            calculateLeaseTerm();
                        }, 100);
                    }
                });

                $('#lease_end_date').datepicker({
                    dateFormat: "dd-M-yy",
                    minDate : new Date('{{ \Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)->lastOfMonth()->format('Y-m-d')}}'),
                    {!!  getYearRanage() !!}
                    changeYear: true,
                    changeMonth: true,
                    onSelect: function (date, instance) {
                        var _ajax_url = '{{route("lease.checklockperioddate")}}';
                        checklockperioddate(date, instance, _ajax_url);
                        calculateLeaseTerm();
                    }
                });

                function calculateLeaseTerm() {
                    $.ajax({
                        url: "{{route('addlease.leaseasset.getdatedifference')}}",
                        data: {
                            accural_period: $('#accural_period').val(),
                            lease_end_date: $('#lease_end_date').val()

                        },
                        type: 'get',
                        success: function (response) {
                            $("#lease_term").val(response.html);
                        }
                    });
                }

                $('#accural_period').datepicker({
                    dateFormat: "dd-M-yy",
                    changeYear: true,
                    onSelect: function () {

                    }
                });

                toggleUsinLeasePayment();

                $('#lease_free_period').on('keyup', function () {
                    resetAllDates();
                });

                function resetAllDates() {
                    var startDate = $('#lease_start_date').datepicker('getDate');
                    var newdate = new Date(startDate);
                    newdate.setDate(startDate.getDate() + parseInt($('#lease_free_period').val()));
                    $('#accural_period').datepicker('setDate', newdate);
                    //set the minimum date for the lease end date as well

                    //lease end date should be +30 days of accural peroid date
                    var dt2 = $('#lease_end_date');
                    var endDate = $('#lease_end_date').datepicker('getDate');
                    var dt3 = new Date($('#accural_period').datepicker('getDate'));
                    var dt4 = new Date(dt3.setDate(dt3.getDate() + 30));

                    if (dt4 <= new Date('{{ \Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)->lastOfMonth()->format("Y-m-d")}}')) {
                        dt2.datepicker('option', 'minDate', new Date('{{ \Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)->lastOfMonth()->format("Y-m-d")}}'));
                    } else {
                        dt2.datepicker('option', 'minDate', dt4);
                    }

                    @if(old('lease_end_date', $asset->lease_end_date))
                    dt2.datepicker('setDate', new Date('{{ $asset->lease_end_date }}'));
                    @endif

                    //@todo check for the accural_period if that is prior to jan 01, 2019 than show the accounting period fields
                    toggleUsinLeasePayment();
                }
            });

            $('select[name="accounting_treatment"]').on('change', function () {
                var lease_asset_accounting = $("#accounting_treatment").find('option:selected').text();
                if (lease_asset_accounting == 'Finance Lease Accounting') {
                    var modal = bootbox.dialog({
                        message: "Finance Lease will be revalued at present value as on {{ \Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)->format('F d, Y') }}",
                        buttons: [
                            {
                                label: "OK",
                                className: "btn btn-success pull-left",
                                callback: function () {
                                }
                            },

                        ],
                        show: false,
                        onEscape: function () {
                            modal.modal("hide");
                        }
                    });

                    modal.modal("show");
                }
            });
        });
        $('.save_next').on('click', function (e) {
            e.preventDefault();
            $('input[name="action"]').val('next');
            $('#lease_asset').submit();
        });
    </script>
@endsection