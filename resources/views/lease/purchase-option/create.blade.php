@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Purchase Option</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @include('lease._subsequent_details')

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                    @include('lease.purchase-option._form')
                </div>
            </div>

        </div>
    </div>
@endsection