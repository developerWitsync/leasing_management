<div class="locatPurposeOutBx">
    <div class="locatpurposeTop leaseterminatHd">
        {{ $final_data['valuation_type'] }}
        <span>{{ $final_data['valuation_type'] }} as on  : {{\Carbon\Carbon::parse($final_data['effective_date'])->format(config('settings.date_format'))}}</span>
    </div>
    <div class="leasepaymentTble">
        <table cellpadding="0" cellspacing="0" border="0" width="100%">
            <tr>
                <th>Lease Payment <br/> Name</th>
                    <th>Effective Lease <br/> Start Date</th>
                <th>Lease <br/> End Date</th>
                <th>Undiscounted <br/> Lease Liability (LC)</th>
                <th>PV of Lease <br/> Liability (LC)</th>

                @if($show_statutory)
                    <th>Exchange <br/> Rate</th>
                    <th>Undiscounted <br/> Lease Liability (SC)</th>
                    <th>PV of Lease <br/> Liability (SC)</th>
                @endif
            </tr>
            @php
                $total_ud  = $total_pv = $total_sc_ud =  $total_sc_pv = 0;
            @endphp
            @foreach($final_data['payments'] as $payment)
                <tr>
                    <td><strong>{{ $payment['payment_name'] }}</strong></td>
                    <td>{{\Carbon\Carbon::parse($payment['effective_lease_start_date'])->format(config('settings.date_format'))}}</td>
                    <td>{{\Carbon\Carbon::parse($payment['lease_end_date'])->format(config('settings.date_format'))}}</td>
                    <td>{{number_format($payment['undiscounted_lease_liability'], 2)}}</td>
                    <td>{{number_format($payment['present_value'], 2)}}</td>

                    @if($show_statutory)
                        <td>{{ $exchange_rate }}</td>
                        <td>{{ number_format((float)$exchange_rate * (float)$payment['undiscounted_lease_liability'], 2) }}</td>
                        <td>{{ number_format((float)$exchange_rate * (float)$payment['present_value'], 2)  }}</td>
                        @php
                            $total_sc_ud += (float)$exchange_rate * (float)$payment['undiscounted_lease_liability'];
                            $total_sc_pv += (float)$exchange_rate * (float)$payment['present_value'];
                        @endphp
                    @endif
                    @php
                        $total_ud += (float)$payment['undiscounted_lease_liability'] ;
                        $total_pv += (float)$payment['present_value'] ;
                    @endphp
                </tr>
            @endforeach
            <tr>
                <td colspan="3" class="totalLease"><strong>Total Lease Liability</strong></td>
                <td>{{number_format($total_ud, 2)}}</td>
                <td>{{number_format($total_pv, 2)}}</td>

                @if($show_statutory)
                    <td>&nbsp;</td>
                    <td>{{ number_format($total_sc_ud , 2)}}</td>
                    <td>{{ number_format($total_sc_pv ,  2)}}</td>
                @endif
            </tr>
        </table>
    </div>
</div>


<div class="presentValue">
    <ul class="clearfix">
        <li>
            <span>Present Value of Lease Liability</span>
            <strong>{{ $final_data['lease_currency'] }} {{ number_format($total_pv,2) }}</strong>
        </li>
        @if(!empty($final_data['lease_balances']))
            <li>
                <span>Lease Balances as on {{ \Carbon\Carbon::parse($account_base_date)->subDay(1)->format(config('settings.date_format')) }} </span>
                <strong>
                    {{$final_data['lease_balances']['reporting_currency_selected']}} {{ number_format($final_data['lease_balances']['accrued_lease_payment_balance'],2) }}
                </strong>
            </li>
        @endif

        @if(!empty($final_data['initial_direct_cost']) && $final_data['initial_direct_cost']['initial_direct_cost_involved'] == "yes")
            <li>
                <span>Initial Direct Cost</span>
                <strong>{{$statutory_currency}} {{ number_format((float)$exchange_rate *  (float)$final_data['initial_direct_cost']['total_initial_direct_cost'], 2) }}</strong>
            </li>
        @endif

        @if(!empty($final_data['lease_incentives']) && $final_data['lease_incentives']['is_any_lease_incentives_receivable'] == "yes")
            <li>
                <span>Lease Incentives</span>
                <strong>{{$statutory_currency}} {{ number_format( (float)$exchange_rate *  (float)$final_data['lease_incentives']['total_lease_incentives'], 2)}}</strong>
            </li>
        @endif

        @if(!empty($final_data['dismantling_cost']) && $final_data['dismantling_cost']['cost_of_dismantling_incurred'] == "yes")
            <li>
                <span>Estimated Dismantling Costs</span>
                <strong>{{$statutory_currency}} {{ number_format((float)$exchange_rate *  (float)$final_data['dismantling_cost']['total_estimated_cost'], 2) }}</strong>
            </li>
        @endif

        <li>
            <span>Value of Lease Asset</span>
            <strong>{{$statutory_currency}} {{ number_format((float)$exchange_rate * (float)$final_data["value_of_lease_asset"], 2)  }}</strong>
        </li>

    </ul>
</div>