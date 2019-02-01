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

    <div class="content-wrapper">
    <div class="container-fluid">
    <div class="row">

        <!-- Icon Cards-->
        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
               <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                    <h4>Employee</h4>
                    <h2>20</h2>
                </div>
              </div>
            </div>
        </div>

        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                    <h4>Clients</h4>
                    <h2>120</h2>
                </div>
              </div>
            </div>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-12 mb-2 mt-4">
            <div class="inforide">
              <div class="row">
                
                <div class="col-lg-9 col-md-8 col-sm-8 col-8 fontsty">
                    <h4>Jobs</h4>
                    <h2>50</h2>
                </div>
              </div>
            </div>
        </div>

    </div>
  </div>
</div>

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


