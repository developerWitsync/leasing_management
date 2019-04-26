<div class="panel panel-info">
    <div class="panel-heading">
        Valuation Of Lease Asset
        @if(!$subsequent_modify_required)|
        <small>Using Modified Retrospective Approach (equivalent to Present Value of Lease Liability)</small>
        @endif
    </div>
    @if(!$subsequent_modify_required)
        <table class="table table-bordered table-responsive">

            <tr>
                <th style="text-align: center">Present Value of Lease Liability</th>
                <th style="text-align: center">Prepaid Lease Payments</th>
                {{--<th style="text-align: center">Accrued Lease Payments</th>--}}
                <th style="text-align: center">Outstanding Lease Liability</th>
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
                    <td style="border: 1px solid #ddd;text-align: center;">{{ number_format($asset->leaseBalanceAsOnDec->outstanding_lease_payment_balance *  $asset->leaseBalanceAsOnDec->exchange_rate, 2) }}</td>
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
    @else
        <table class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th style="text-align:center;background: #13aaa9;color: #fff;font-weight: 600;" colspan="3">PART A:
                    Change in Lease Liability
                </th>
            </tr>
            <tr>
                <th style="text-align: center">Existing Lease Liability Balance</th>
                <th style="text-align: center">Present Value of Lease Liability</th>
                <th style="text-align: center">Increase/Decrease in Lease Liability</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="text-align: center"
                    class="existing_lease_liability">{{$existing_lease_liability_balance}}</td>
                <td style="text-align: center" class="load_lease_liability">Loading...</td>
                <td style="text-align: center" class="increase_decrease_part_first">Loading...</td>
            </tr>
            </tbody>
        </table>

        <table class="table table-bordered table-responsive">
            <thead>
            <tr>
                <th style="text-align:center;background: #13aaa9;color: #fff;font-weight: 600;" colspan="5">PART B: New
                    Value of Lease Asset
                </th>
            </tr>
            <tr>
                <th style="text-align: center">Existing Value of Lease Asset</th>
                <th style="text-align: center">Existing Carrying Value of Lease Asset</th>
                <th style="text-align: center">Increase/Decrease in Lease Liability</th>
                <th style="text-align: center">New Value of Lease Asset</th>
                <th style="text-align: center">Charge to PL</th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td style="text-align: center"
                    class="existing_lease_liability">{{$existing_value_of_lease_asset}}</td>
                <td style="text-align: center">{{$existing_carrying_value_of_lease_asset}}</td>
                <td style="text-align: center" class="increase_decrease_part_first">Loading...</td>
                <td style="text-align: center" class="new_value_of_lease_asset">Loading...</td>
                <td style="text-align: center" class="charge_to_pl">Loading...</td>
            </tr>
            </tbody>
        </table>
    @endif
</div>
