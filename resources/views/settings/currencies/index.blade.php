@extends('layouts.app')

@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css" href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
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
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @include('settings._menubar')

            <div class="">
                <div role="tabpanel" class="tab-pane active">
                    <!-- Reporting Currency Settings -->
                    @include('settings.currencies._reporting_currencies')
                    <!-- Reporting Currency Settings Ends Here -->
                    {{-- Commented to show the foreign currency involved without creating the currency settings --}}
                    {{--@if(isset($reporting_currency_settings->business_account_id))--}}
                        {{--<!-- Foreign Currency Transaction Settings -->--}}
                        {{--@include('settings.currencies._foreign_transaction_settings')--}}
                        {{--<!-- Foreign Currency Transaction Settings Ends Here -->--}}
                    {{--@endif--}}

                    @include('settings.currencies._foreign_transaction_settings')

                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer-script')
    <!-- BEGIN Java Script for this page -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.4.0/Chart.min.js"></script>
    <script src="{{ asset('assets/plugins/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/bootbox/bootbox.min.js') }}"></script>
    <script>

        $(function(){

            //new code added since we have removed the internal currency on the currencies settings....
            $("#statutory_financial_reporting_currency").on('change', function(){
                if($(this).val()!=""){
                    var selected_value = $(this).val();
                    bootbox.confirm({
                        message: "Please Confirm “"+$(this).val()+"” is Your Statutory Reporting Currency, since once selected cannot be changed.",
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
                            if(result) {
                                $("#currency_for_lease_reports").val(selected_value);
                            } else {
                                $("#statutory_financial_reporting_currency").val('');
                                $("#currency_for_lease_reports").val('');
                            }
                        }
                    });

                }
            });

            $("input[name='internal_same_as_statutory_reporting']").on('change', function(){
                if($(this).is(':checked')) {
                    $("input[name='internal_same_as_statutory_reporting']").prop('checked', false);
                    $(this).prop('checked', true);
                } else {
                    $("input[name='internal_same_as_statutory_reporting']").prop('checked', false);
                }

                if($(this).val() == 'yes' && $(this).is(':checked')) {
                    $(".internal_company_currency_select").addClass('hidden');
                    $("#internal_company_financial_reporting_currency").val($("#statutory_financial_reporting_currency").val());
                } else if($(this).val() == 'no' && $(this).is(':checked')){
                    $(".internal_company_currency_select").removeClass('hidden');
                    $("#internal_company_financial_reporting_currency").val('');
                } else {
                    $(".internal_company_currency_select").addClass('hidden');
                    $("#internal_company_financial_reporting_currency").val('');
                }

            });

            $("input[name='lease_report_same_as_statutory_reporting']").on('change', function(){
                if($(this).is(':checked')) {
                    $("input[name='lease_report_same_as_statutory_reporting']").prop('checked', false);
                    $(this).prop('checked', true);
                } else {
                    $("input[name='lease_report_same_as_statutory_reporting']").prop('checked', false);
                }

                if(($(this).val() == '1' || $(this).val() == '2') && $(this).is(':checked')) {
                    $(".currency_for_lease_reports").addClass('hidden');

                    if($(this).val() == '1') {
                        $("#currency_for_lease_reports").val($("#statutory_financial_reporting_currency").val());
                    } else if($(this).val() == '2'){
                        $("#currency_for_lease_reports").val($("#internal_company_financial_reporting_currency").val());
                    }

                } else if($(this).val() == '3' && $(this).is(':checked')){
                    $(".currency_for_lease_reports").removeClass('hidden');
                    $("#currency_for_lease_reports").val('');
                } else {
                    $(".currency_for_lease_reports").addClass('hidden');
                }

            });

            $("input[name='is_involved']").on('change', function(){
                if($(this).is(':checked')) {
                    $("input[name='is_involved']").prop('checked', false);
                    $(this).prop('checked', true);
                } else {
                    $("input[name='is_involved']").prop('checked', false);
                }

                $.ajax({
                    url : '{{ route("settings.currencies.updateisforeigninvolved") }}',
                    dataType : 'json',
                    type : 'post',
                    data : {
                        is_foreign_transaction_involved : $(this).val()
                    },
                    success : function (response){
                        if(response['status']) {
                            window.location.reload();
                        }  else {
                            bootbox.alert(response['message']);
                        }
                    }
                })
            });

        });

    </script>
    <script>
        $(document).ready(function () {

            var foreign_transaction_currency = $('#foreign_transaction_currency').DataTable({
                responsive: true,
                "columns": [
                    {
                        "data": "id",
                        render: function (data, type, row, meta) {
                            return meta.row + meta.settings._iDisplayStart + 1;
                        }
                    },
                    { "data": "foreign_exchange_currency"},
                    { "data": "base_currency"},
                    // { "data": "exchange_rate"},
                    // { "data": "valid_from"},
                    // { "data": "valid_to"},
                    { "data": "id"}
                ],
                "columnDefs": [
                    {
                        "targets" : 3,
                        "data" : null,
                        "orderable": false,
                        "className" : "text-center",
                        "render" : function(data, type, full, meta) {
                            var html = '';
                            if(full['is_used'] == 0){
                                var html = "<button  data-toggle='tooltip' data-placement='top' title='Edit Country' type=\"button\" data-currency='"+full['id']+"' class=\"btn btn-sm btn-success edit_currency\"><i class=\"fa fa-pencil-square-o fa-lg\"></i></button>";
                                html += "&nbsp;&nbsp;<button  data-toggle='tooltip' data-placement='top' title='Delete Currency' type=\"button\" data-currency='"+full['id']+"' class=\"btn btn-sm btn-danger delete_currency\">  <i class=\"fa fa-trash-o fa-lg\"></i></button>";
                            }
                            return html;
                        }
                    }
                ],
                "processing": true,
                "serverSide": true,
                "ajax": "{{ route('settings.currencies.fetchforeigntransactioncurrencies') }}"

            });

            $(document.body).on('click', '.edit_currency', function(){
                var currency_id = $(this).data('currency');
                window.location.href = "/settings/currencies/edit-foreign-transaction-currencies/"+currency_id;
            });

            $(document.body).on("click", ".delete_currency", function () {
                var currency_id = $(this).data('currency');
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
                        if(result) {
                            $.ajax({
                                url : "/settings/currencies/delete-foreign-transaction-currencies/"+currency_id,
                                type : 'delete',
                                dataType : 'json',
                                success : function (response) {
                                    if(response['status']) {
                                        foreign_transaction_currency.ajax.reload();
                                    }
                                }
                            })
                        }
                    }
                });
            });

        });
    </script>

@endsection