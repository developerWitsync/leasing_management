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
                    <div class="panel-heading">Section A: Leases for own use lease</div>
                    <table class="table table-bordered table-responsive">
                        <thead>
                        <tr>
                            <th>Sr. No.</th>
                            <th>Unique ULA Code</th>
                            <th>Name of the Underlying Lease Asset</th>
                            <th>Underlying Lease Asset Classification</th>
                        </tr>
                        </thead>
                        <tbody>
                            @foreach($own_assets as $key=>$asset)
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
                                {{-- CHECK FOR THE CONDITION IF THE CUMULATIVE NEEDS TO BE SHOWN FOR THIS ASSET--}}
                                @if($asset->using_lease_payment == 1 &&  \Carbon\Carbon::cr)
                                <tr class="sub_table">
                                    <td colspan="4" class="tableInner">

                                        <table width="100%">
                                            <tr>
                                                <td class="sub_table_heading">
                                                    Cummulative Effect Method - Carrying Value Method
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <table class="table table-bordered table-responsive">
                                                        <thead>
                                                        <th>Carrying Amount of a Lease Asset as on Jan 01, 2019</th>
                                                        <th>Present Value of Lease Liability</th>
                                                        <th>Adjustment to Equity</th>
                                                        <th>Action</th>
                                                        </thead>
                                                        <tr>
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <td>0</td>
                                                            <td>&nbsp;</td>
                                                        </tr>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>

                                    </td>
                                </tr>
                            @endforeach
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
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        @php
                            $show_next = [];
                        @endphp
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
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>