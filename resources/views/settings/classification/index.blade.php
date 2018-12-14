@extends('layouts.app')

@section('content')
        <div class="panel panel-default">
            <div class="panel-heading">Settings | Lease Classifications Settings</div>

            <div class="panel-body">
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                @include('settings._menubar')

                <div class="tab-content">
                    <div role="tabpanel" class="tab-pane active">
                        <div class="panel panel-info">
                            <div class="panel-heading">Percentage Rate Types</div>
                            <div class="panel-body">
                                <table class="table table-condensed">
                                    <thead>
                                        <tr>
                                            <th>Sr No.</th>
                                            <th>Title</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($rates as $key =>$rate)
                                            <tr>
                                                <td>{{ $key + 1 }}</td>
                                                <td>{{ $rate->title }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Lease Contract Classification</div>
                            <div class="panel-body">
                                <table class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Title</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($contract_classifications as $key =>$classification)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $classification->title }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Use of Lease Asset</div>
                            <div class="panel-body">
                                <table class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Title</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lease_asset_use as $key =>$value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->title }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="panel panel-info">
                            <div class="panel-heading">Components of Lease Payments</div>
                            <div class="panel-body">
                                <table class="table table-condensed">
                                    <thead>
                                    <tr>
                                        <th>Sr No.</th>
                                        <th>Title</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($lease_payment_component as $key =>$value)
                                        <tr>
                                            <td>{{ $key + 1 }}</td>
                                            <td>{{ $value->title }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
@endsection
