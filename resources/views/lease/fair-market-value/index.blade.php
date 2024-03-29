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

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane frmOuterBx active">
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Lease Asset</th>
                            <th>LA Classification</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                            @php
                                $show_next = [];
                            @endphp
                            @foreach($lease->assets as $key=>$asset)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td style="width: 10%">
                                        {{ $asset->uuid}}
                                    </td>
                                    <td>
                                        {{ $asset->name }}
                                    </td>
                                    <td>
                                        {{ $asset->subcategory->title }}
                                    </td>
                                    <td>
                                        @if($asset->fairMarketValue)
                                            @php
                                                $show_next[] = true;
                                            @endphp
                                            <a class="btn btn-sm btn-primary" href="{{ route('addlease.fairmarketvalue.update', ['id'=> $asset->id]) }}">Update Fair Market Value Details</a>
                                        @else
                                            @php
                                                $show_next[] = false;
                                            @endphp
                                            <a class="btn btn-sm btn-primary" href="{{ route('addlease.fairmarketvalue.create', ['id'=> $asset->id]) }}">Add Fair Market Value Details</a>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="form-group">

                        <div class="col-md-6 col-md-offset-4">

                            <a href="{{ route('addlease.payments.index', ['id' => $lease->id]) }}" class="btn btn-danger">Back</a>

                            @if(!in_array(false, $show_next))
                                <a href="{{ route('addlease.residual.index', ['id' => $lease->id]) }}" class="btn btn-primary">Next</a>
                            @endif
                        </div>

                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
