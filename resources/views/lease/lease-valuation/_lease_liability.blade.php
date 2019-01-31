<div class="panel panel-default">
    <div class="panel-heading">Present Value of Lease Liability</div>

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
                <div class="panel panel-info">
                    <div class="panel-heading">Section A: Leases for own use lease</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Name of the Underlying Lease Asset</th>
                            <th>Underlying Lease Asset Classification</th>
                            <th>Currency</th>
                            <th>Present Value of Lease Liability</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @if(count($own_assets) > 0)
                            @foreach($own_assets as $key=>$asset)
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
                                    <td>{{ $asset->lease->lease_contract_id }}</td>
                                    <td class="load_lease_liability" data-asset_id="{{ $asset->id }}"></td>
                                    <td>
                                        <a class="btn btn-sm btn-primary" href="javascript:void(0);"  onclick="javascript:showPresentValueCalculus('{{ $asset->id }}');">PV Calculus</a>
                                        <i class="fa fa-spinner fa-spin calculus_spinner_{{$asset->id}}" style="font-size:24px;display: none;"></i>
                                    </td>
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
                                    <center>No Records exists.</center>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>

                <div class="panel panel-info">
                    <div class="panel-heading">Section B: Leases for sub leases use</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Name of the Underlying Lease Asset</th>
                            <th>Underlying Lease Asset Classification</th>
                            <th>Currency</th>

                        </tr>
                        </thead>
                        <tbody>
                        @if(count($sublease_assets) > 0)
                            @foreach($sublease_assets as $key=>$asset)
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
                                </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6">
                                    <center>
                                        No Records exists.
                                    </center>
                                </td>
                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>