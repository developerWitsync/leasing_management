@extends('layouts.app')

@section('header-styles')
    <!-- BEGIN CSS for this page -->
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
    <!-- END CSS for this page -->
@endsection

@section('content')
    <div class="panel panel-default">
        {{--<div class="panel-heading">Currencies Settings</div>--}}

        <div class="panel-body">

            @include('settings._menubar')

            @if (session('status'))
                <div class="alert alert-success" style="margin-bottom: 0px;margin-top: 16px;">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger" style="margin-bottom: 0px;margin-top: 16px;">
                    {{ session('error') }}
                </div>
            @endif

            <div class="">
                <div role="tabpanel" class="tab-pane active">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            Set Exchange Rates
                            {!! renderToolTip('Set the exchange rates for the foreign currencies that you have selected.') !!}
                        </div>
                        <div class="panel-body" style="padding: 20px;">
                            <div class="tab-content" style="padding: 0px;">
                                <div role="tabpanel" class="tab-pane active">
                                    <form class="form-horizontal" method="POST"
                                          action="{{ route('settings.currencies.saveexchangerates', ['id' => $model->id]) }}"
                                          id="lease_asset" enctype="multipart/form-data">
                                        {{ csrf_field() }}

                                        <div class="categoriesOuter clearfix">
                                            <div class="form-group{{ $errors->has('base_currency') ? ' has-error' : '' }} required">
                                                <label for="base_currency" class="col-md-12 control-label"
                                                       style="display: inline;">Base Currency</label>
                                                {!! renderToolTip('The Base Currency against which the exchange rates have to be provided.', 'input_info_tooltip', 'top' , 'display:inline;') !!}
                                                <div class="col-md-12">
                                                    <input id="base_currency" type="text" placeholder="Base Currency"
                                                           class="form-control"
                                                           name="base_currency"
                                                           value="{{ old('base_currency', $model->base_currency) }}"
                                                           disabled="disabled">
                                                    @if ($errors->has('base_currency'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('base_currency') }}</strong>
                                                        </span>
                                                    @endif

                                                </div>
                                            </div>

                                            <div class="form-group{{ $errors->has('foreign_exchange_currency') ? ' has-error' : '' }} required">
                                                <label for="foreign_exchange_currency" class="col-md-12 control-label"
                                                       style="display: inline;">Foreign Currency</label>
                                                {!! renderToolTip('The Foreign Currency for which the exchange rates have to be provided.', 'input_info_tooltip', 'top' , 'display:inline;') !!}
                                                <div class="col-md-12">
                                                    <input id="foreign_exchange_currency" type="text"
                                                           placeholder="Foreign Currency"
                                                           class="form-control"
                                                           name="foreign_exchange_currency"
                                                           value="{{ old('foreign_exchange_currency', $model->foreign_exchange_currency) }}"
                                                           disabled="disabled">
                                                    @if ($errors->has('foreign_exchange_currency'))
                                                        <span class="help-block">
                                                            <strong>{{ $errors->first('foreign_exchange_currency') }}</strong>
                                                        </span>
                                                    @endif

                                                </div>
                                            </div>

                                            <div class="import_via_excel import_option_first clearfix" style="">
                                                <div class="headerImport">
                                                    <h1>Import Exchange Rates</h1>
                                                </div>
                                                <div class="bodyImport">
                                                    <div class="first">
                                                        You can download the excel template to provide the exchange rates for the dates.
                                                    </div>
                                                    <div class="second">
                                                        <strong>Please Note:</strong> Do not change the exchange rate dates in the excel template, the dates in the template .
                                                        are system generated and depends on your min and max years settings.
                                                    </div>

                                                    <div class="import_via_excel" style="padding-bottom: 30px;">
                                                        <label class="col-md-12 control-label">&nbsp;</label>
                                                        <div class="row">
                                                            <div class="col-md-7">
                                                                <a href="{{ route('settings.currencies.downloadexcel', ['id' => $model->id]) }}" class="btn btn-primary ">
                                                                    <i class="fa fa-download"></i>
                                                                    Download Excel For Importing
                                                                </a>
                                                            </div>

                                                            <div class="col-md-5">
                                                                <div class=" frmattachFile">
                                                                    <input type="name" id="uploadX" name="name" class="form-control" disabled="disabled">
                                                                    <button type="button" class="browseBtn"><i class="fa fa-upload"></i> Browse</button>
                                                                    <input type="file" id="import_dates" name="import_excel" class="fileType">
                                                                    <h6 class="disabled">Only Xlsx with 2MB size of files are allowed.</h6>
                                                                    @if ($errors->has('import_excel'))
                                                                        <span class="help-block">
                                                                            <strong>{{ $errors->first('import_excel') }}</strong>
                                                                        </span>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="form-group btnMainBx">

                                            <div class="col-md-6 col-sm-6 btn-backnextBx">
                                                &nbsp;
                                            </div>
                                            <div class="col-md-6 btnsubmitBx">
                                                <button type="submit" class="btn btn-success" name="submit" value="save" onclick="showOverlayForAjax();">Save
                                                </button>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                                @if($showExchangeRates)
                                    @include('settings.currencies._show_exchange_rates')
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer-script')
<script>
    $(function(){

        $('#import_dates').change(function () {
            $('#import_dates').show();
            var filename = $('#import_dates').val();
            var or_name = filename.split("\\");
            $('#uploadX').val(or_name[or_name.length - 1]);
        });

        $('.render_exchange_rates').on('click', function(){
            $('.render_exchange_rates').removeClass('active');
            $('li').removeClass('active');
            $(this).addClass('active');
            $(this).parent('li').addClass('active');
            var f_cid = $(this).data('f_cid');
            var year =$(this).data('year');
            renderExchangeRates(f_cid, year);
        });

        $('document').ready(function(){
            var f_cid = $('.render_exchange_rates.active').data('f_cid');
            var year = $('.render_exchange_rates.active').data('year');
            renderExchangeRates(f_cid, year);
        });

        function renderExchangeRates(f_cid, year){
            $.ajax({
                url : '{{ route('settings.currencies.getexchangerates') }}',
                data : {
                    id : f_cid,
                    year : year
                },
                beforeSend: function(){
                    showOverlayForAjax();
                },
                success : function(response){
                    removeOverlayAjax();
                    $('.append_here').html(response);
                }
            });
        }
    });
</script>
@endsection