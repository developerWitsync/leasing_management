@extends('layouts.error')
@section('content')
    <div class="errorTxt">
        <i><img src="{{ asset('assets/images/opps-Icon.png') }}" alt=""></i>
        <strong>403 - Permission Denied.</strong>

        @if($exception->getMessage()=="")
            <span>Looks like you have not been granted with the permission to access this section. Please contact to your administrator.</span>
        @else
            <span>{{ $exception->getMessage() }}</span>
        @endif
        <a href="/">GO TO HOMEPAGE</a>
    </div>
@endsection