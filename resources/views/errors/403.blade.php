@extends('layouts.error')
@section('content')
    <div class="errorTxt">
        <i><img src="{{ asset('assets/images/opps-Icon.png') }}" alt=""></i>
        <strong>403 - Permission Denied.</strong>
        <span>Looks like you have not been granted with the permission to access this section. Please contact to your administrator.</span>
        <a href="{{ route('home') }}">GO TO HOMEPAGE</a>
    </div>
@endsection