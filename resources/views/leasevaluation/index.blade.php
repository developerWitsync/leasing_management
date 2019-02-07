@extends('layouts.app')

@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Lease Valuation</div>
        <div class="">
            <ul class="nav nav-tabs">
                <li @if((!request()->has('capitalized')) || (request()->has('capitalized') && request()->query('capitalized') == 1)) class="active" @endif>
                    <a href="{{ route('leasevaluation.index',['capitalized' => 1]) }}">Capitalized Lease Asset</a></li>
                <li @if(request()->has('capitalized') && request()->query('capitalized') == 0) class="active" @endif><a
                            href="{{ route('leasevaluation.index',['capitalized' => 0]) }}">Non-Capitalized Lease
                        Asset</a></li>
            </ul>
        </div>

        <div class="panel-body">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                    @include('leasevaluation._menubar')
                    <div class="row">
                        @if($capitalized == '1')
                            @include('leasevaluation._capitalized_assets')
                        @else
                            @include('leasevaluation._non_capitalized_assets')
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
