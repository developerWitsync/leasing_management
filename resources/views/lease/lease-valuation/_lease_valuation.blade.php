<div class="panel panel-default">
    <div class="panel-heading">Valuation Of Lease Asset</div>

    <div class="panel-body">
        @if (session('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div class="tab-content" style="padding: 0px;">
            <div role="tabpanel" class="tab-pane active">
                <div class="panel panel-info">
                    <div class="panel-heading">Valuation Of Lease Asset</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Lease Asset Name</th>
                            <th>Lease Asset Classification</th>
                        </tr>
                        </thead>
                        <tbody>
                            @if(count($assets) > 0)
                                @foreach($assets as $key=>$asset)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td style="width: 10%">
                                            {{ $asset->uuid }}
                                        </td>
                                        <td>
                                            {{ $asset->name }}
                                        </td>
                                        <td>
                                            {{ $asset->subcategory->title }}
                                        </td>
                                    </tr>

                                    @if($asset->using_lease_payment == 1 &&  \Carbon\Carbon::create(2019,1,1)->greaterThan(\Carbon\Carbon::parse($asset->accural_period)))
                                        {{--Case 1 : if lease start date is prior to Base Date AND Initial Payment is selected on D1.13--}}
                                        {{---> Both the option will appear--}}
                                        @include('lease.lease-valuation._cumulative_valuation')
                                        @include('lease.lease-valuation._equivaline_to_present_lease_liability')
                                    @elseif($asset->using_lease_payment == 2 &&  \Carbon\Carbon::create(2019,1,1)->greaterThan(\Carbon\Carbon::parse($asset->accural_period)))
                                        {{--Case 2 : if lease start date is prior to Base Date and Current Method is selected on D1.13--}}
                                        {{---> Equivalent to Present Value of Lease Liability will appear--}}
                                        @include('lease.lease-valuation._equivaline_to_present_lease_liability')
                                    @elseif(\Carbon\Carbon::parse($asset->accural_period)->greaterThanOrEqualTo(\Carbon\Carbon::create(2019, 1, 1)))
                                        {{--Case 3 : When the lease start date is on or after Base Date--}}
                                        {{---> Only Equivalent to Present Value of Lease Liability will appear--}}
                                        @include('lease.lease-valuation._equivaline_to_present_lease_liability')
                                    @endif

                                @endforeach
                            @else
                                <tr>
                                    <td colspan="4">
                                        <center>No Records exists.</center>
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