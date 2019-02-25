@extends('layouts.error')
@section('content')
<div class="errorTxt">
    <i><img src="{{ asset('assets/images/opps-Icon.png') }}" alt=""></i>
    <strong>404 - page not found</strong>
    <span>The page you are looking for might have been removed
        had its name changed or is temporarily unavailable.</span>
    <a href="/">GO TO HOMEPAGE</a>
</div>
@endsection