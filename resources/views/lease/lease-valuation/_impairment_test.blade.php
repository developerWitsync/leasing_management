<div class="panel panel-default">
    <div class="panel-heading">Impairement Test</div>
    <div class="panel-body">
        <table class="table table-bordered table-responsive">
            <thead>
                <tr>
                    <th>Sr. No.</th>
                    <th>Unique ULA Code</th>
                    <th>Name of the Underlying Lease Asset</th>
                    <th>Underlying Lease Asset Classification</th>
                    <th>Value of Lease Asset</th>
                    <th> Fair Market Value of Lease Asset</th>
                    <th>Impairment, if any</th>
                </tr>
            </thead>
            <tbody>
            @php
                $show_next = [];
            @endphp
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
                    <td class="value_of_lease_asset" data-asset_id="{{ $asset->id }}"></td>
                    @if($asset->fairMarketValue)
                        @if($asset->fairMarketValue->is_market_value_present == "yes")
                            <td>{{ number_format($asset->fairMarketValue->total_units, 2) }}</td>
                        @else
                            <td>0</td>
                        @endif
                    @else
                        <td>0</td>
                    @endif
                    <td class="impairment_if_any" data-asset_id="{{ $asset->id }}"></td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>