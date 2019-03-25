<div class="panel panel-info">
    <div class="panel-heading">
        Valuation Of Lease Asset |
        <small>Using Modified Retrospective Approach (equivalent to Present Value of Lease Liability)</small>
    </div>
    <table class="table table-bordered table-responsive">

        <tr>
            <th style="text-align: center">Present Value of Lease Liability</th>
            <th style="text-align: center">Prepaid Lease Payments</th>
            <th style="text-align: center">Accrued Lease Payments</th>
            <th style="text-align: center">Initial Direct Cost</th>
            <th style="text-align: center">Lease Incentives</th>
            <th style="text-align: center">Estimated Cost of Dismantling</th>
            <th style="text-align: center">Value of Lease Asset</th>
        </tr>

        <tr>
            <td style="border: 1px solid #ddd;text-align: center;" class="load_lease_liability"
                data-asset_id="{{ $asset->id }}">Calculating...
            </td>

            @if($asset->leaseBalanceAsOnDec)
                <td style="border: 1px solid #ddd;text-align: center;">{{ number_format($asset->leaseBalanceAsOnDec->prepaid_lease_payment_balance *  $asset->leaseBalanceAsOnDec->exchange_rate, 2) }}</td>
                <td style="border: 1px solid #ddd;text-align: center;">{{ number_format($asset->leaseBalanceAsOnDec->accrued_lease_payment_balance *  $asset->leaseBalanceAsOnDec->exchange_rate, 2) }}</td>
            @else
                <td style="border: 1px solid #ddd;text-align: center;">0</td>
                <td style="border: 1px solid #ddd;text-align: center;">0</td>
            @endif

            @if($asset->initialDirectCost)
                @if($asset->initialDirectCost->initial_direct_cost_involved == "yes")
                    <td style="border: 1px solid #ddd;text-align: center;">{{ number_format($asset->initialDirectCost->total_initial_direct_cost, 2) }}</td>
                @else
                    <td style="border: 1px solid #ddd;text-align: center;">0</td>
                @endif
            @else
                <td style="border: 1px solid #ddd;text-align: center;">0</td>
            @endif

            @if($asset->leaseIncentives)
                @if($asset->leaseIncentives->is_any_lease_incentives_receivable == "yes")
                    <td style="border: 1px solid #ddd;text-align: center;">{{ number_format($asset->leaseIncentives->total_lease_incentives, 2) }}</td>
                @else
                    <td style="border: 1px solid #ddd;text-align: center;">0</td>
                @endif
            @else
                <td style="border: 1px solid #ddd;text-align: center;">0</td>
            @endif

            @if($asset->dismantlingCost)
                @if($asset->dismantlingCost->cost_of_dismantling_incurred == "yes" && $asset->dismantlingCost->obligation_cost_of_dismantling_incurred == "yes")
                    <td style="border: 1px solid #ddd;text-align: center;">{{ number_format($asset->dismantlingCost->total_estimated_cost, 2) }}</td>
                @else
                    <td style="border: 1px solid #ddd;text-align: center;">0</td>
                @endif
            @else
                <td style="border: 1px solid #ddd;text-align: center;">0</td>
            @endif

            <td style="border: 1px solid #ddd;text-align: center;" class="value_of_lease_asset"
                data-asset_id="{{ $asset->id }}"></td>
        </tr>

    </table>
</div>
