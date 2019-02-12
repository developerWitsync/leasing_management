<tr class="sub_table">
    <td colspan="4" class="tableInner">

        <table width="100%">
            <tr>
                <td class="sub_table_heading">
                    Equivalent to Present Value of Lease Liability
                </td>
            </tr>
            <tr>
                <td>
                    <table class="table table-bordered table-responsive">
                        <thead>
                            <th>Present Value of Lease Liability</th>
                            <th>Prepaid Lease Payments</th>
                            <th>Accrued Lease Payments</th>
                            <th>Initial Direct Cost</th>
                            <th>Lease Incentives</th>
                            <th>Value of Lease Asset</th>
                        </thead>
                        <tr>
                            <td class="load_lease_liability" data-asset_id="{{ $asset->id }}"></td>
                            @if($asset->leaseBalanceAsOnDec)
                                <td>{{ number_format($asset->leaseBalanceAsOnDec->prepaid_lease_payment_balance *  $asset->leaseBalanceAsOnDec->exchange_rate, 2) }}</td>
                                <td>{{ number_format($asset->leaseBalanceAsOnDec->accrued_lease_payment_balance *  $asset->leaseBalanceAsOnDec->exchange_rate, 2) }}</td>
                            @else
                                <td>0</td>
                                <td>0</td>
                            @endif

                            @if($asset->initialDirectCost)
                                @if($asset->initialDirectCost->initial_direct_cost_involved == "yes")
                                    <td>{{ number_format($asset->initialDirectCost->total_initial_direct_cost, 2) }}</td>
                                @else
                                    <td>0</td>
                                @endif
                            @else
                                <td>0</td>
                            @endif

                            @if($asset->leaseIncentives)
                                @if($asset->leaseIncentives->is_any_lease_incentives_receivable == "yes")
                                    <td>{{ number_format($asset->leaseIncentives->total_lease_incentives, 2) }}</td>
                                @else
                                    <td>0</td>
                                @endif
                            @else
                                <td>0</td>
                            @endif

                            <td class="value_of_lease_asset" data-asset_id="{{ $asset->id }}"></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>