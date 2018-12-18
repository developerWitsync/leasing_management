@extends('layouts.admin.master')

@section('title')
    @lang('_page_titles.Admin') | @lang('_page_titles.Email Templates')
@endsection

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
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-12">
                <div class="breadcrumb-holder">
                    <h1 class="main-title float-left">Contact Us</h1>
                    <ol class="breadcrumb float-right">
                        <li class="breadcrumb-item">@lang('_sidebar.Dashboard')</li>
                        <li class="breadcrumb-item active">Contact Us</li>
                    </ol>
                    <div class="clearfix"></div>
                </div>
            </div>
        </div>
        <!-- end row -->
       

        <div class="row">

            <div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card mb-3">
                    <div class="card-header">
                        <h3><i class="fa fa-file-o"></i> View Details </h3>
                        
                    </div>

                    <div class="card-body">
                        <table cellpadding="0" cellspacing="0"  class="table table-bordered table-hover display">
                            <thead>
                            <tr>
                               
                                <th>First name</th>
                                <th>Last name</th>
                                <th>Eamil</th>
                                <th>Phone</th>
                                <th>Comments</th>
                            </tr>
                            <tr>

                            	<td>{{ $template->first_name}} </td>
                            	<td> {{ $template->last_name}}</td>
                            	<td> {{ $template->email}}</td>
                            	<td> {{ $template->phone}}</td>
                            	<td> {{ $template->comments}}</td>
                            </tr>
                            </thead>
                        </table>
                    </div>
                </div><!-- end card-->
            </div>

        </div>
    </div>
@endsection
