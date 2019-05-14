<div class="panel panel-info">
    <div class="panel-heading">Impairement Test Of Lease Asset</div>
    <table class="table table-bordered table-responsive">

        <tr>
            <th style="text-align: center">Value of Lease Asset</th>
            <th style="text-align: center">FM value Lease Asset</th>
            <th style="text-align: center">Impairment, if any</th>
        </tr>

        <tr>
            <td @if($asset->using_lease_payment == 2)  class="value_of_lease_asset_under_first_method" @else class="value_of_lease_asset" @endif data-asset_id="{{ $asset->id }}" style="border: 1px solid #ddd;text-align: center;"></td>
            @if($asset->fairMarketValue)
                @if($asset->fairMarketValue->is_market_value_present == "yes")
                    <td style="border: 1px solid #ddd;text-align: center;">{{ number_format($asset->fairMarketValue->total_units, 2) }}</td>
                @else
                    <td style="border: 1px solid #ddd;text-align: center;">0</td>
                @endif
            @else
                <td style="border: 1px solid #ddd;text-align: center;">0</td>
            @endif
            <td class="impairment_if_any" data-asset_id="{{ $asset->id }}" style="border: 1px solid #ddd;text-align: center;"></td>
        </tr>

    </table>
</div>