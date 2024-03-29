@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
@endsection

@section('content')
    <div class="panel panel-default">
        @if($settings->date_of_initial_application == 2)
            <div class="panel-heading">Lease Balance as on {{ \Carbon\Carbon::parse(getParentDetails()->baseDate->final_base_date)->subYear(1)->subDay(1)->format(config('settings.date_format')) }} </div>
        @else
            <div class="panel-heading">Lease Balance as on {{ \Carbon\Carbon::parse(getParentDetails()->baseDate->final_base_date)->subDay(1)->format(config('settings.date_format')) }} </div>
        @endif

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @include('lease._subsequent_details')

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                    @include('lease.lease-balnce-as-on-dec._form')
                </div>
            </div>

        </div>
    </div>
@endsection