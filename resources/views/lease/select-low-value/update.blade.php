@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
@endsection

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Edit Select Low Value </div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                    @include('lease.select-low-value._form')
                </div>
            </div>

        </div>
    </div>
@endsection