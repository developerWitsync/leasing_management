@extends('layouts.app')
@section('header-styles')
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">
        <div class="panel-heading">Fair Market Value of Underlying Lease Asset</div>

        <div class="panel-body">
            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach (array_unique($errors->all()) as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{--@include('lease._menubar')--}}

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">

                <table class="table table-bordered table-responsive">
                    <thead>
                    <tr>
                        <th>UNDERLYING LEASE ASSET NUMBER</th>
                        <th>Unique ULA Code</th>
                        <th>Name of the Underlying Lease Asset</th>
                        <th>Underlying Leased Asset Classification</th>
                        <th>Is Fair Market Value Available</th>
                        <th>Enter SOURCE OF FMV</th>
                        <th>Upload</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach($lease->assets as $asset)
                            <tr>
                                <td>{{ $asset->uuid }}</td>
                                <td>{{ $asset->uuid }}</td>
                                <td>{{ $asset->uuid }}</td>
                                <td>{{ $asset->uuid }}</td>
                                <td>{{ $asset->uuid }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            </div>


        </div>
    </div>
@endsection
