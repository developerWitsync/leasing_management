@extends('layouts.app')

@section('content')
        <div class="panel panel-default">
            <div class="panel-heading">Dashboard</div>

            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                You are logged in!
                <div class="totlNumbOuter clearfix">
                    <div class="totlNumbInnBx">
                        <h3>Total Number Of Active Lease Assets</h3>
                        <span>{{$total_active_lease_asset}}</span>
                    </div>
                    <div class="totlNumbInnBx">
                        <h3>Capitalized Assets</h3>
                        <span>Total Own Lease-  {{ $own_assets_capitalized }}
                       Total Sub Lease-  {{ $sublease_assets_capitalized }}</span>
                    </div>
                    <div class="totlNumbInnBx">
                        <h3>Land</h3>
                        <span>{{$total_land}}</span>
                    </div>
                </div>
            </div>
        </div>
@endsection
