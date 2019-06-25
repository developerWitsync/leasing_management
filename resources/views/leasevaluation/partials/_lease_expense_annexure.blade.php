<table cellpadding="0" cellspacing="0" border="0">
    <thead>
    <tr>
        <th width="5%">Year</th>
        <th width="5%">Lease Payment Dates</th>
        @foreach($lease_payments->payments_details as $detail)
            <th width="5%">{{ $detail['payment_name'] }}</th>
        @endforeach
        <th>Termination Penalty</th>
        <th>Residual Value</th>
        <th width="5%">Total Lease Payments</th>
        <th width="5%">Opening Prepaid / (Payable) Balance</th>
        @foreach($lease_payments->payments_details as $detail)
            <th width="5%">Computed Lease {{ $detail['payment_name'] }} Expense</th>
        @endforeach
        <th>Termination Penalty Computed Lease Expense</th>
        <th>Residual Value Computed Lease Expense</th>
        <th width="5%">Total Computed Lease Expense</th>
        <th width="5%">Closing Prepaid / (Payable) Balance</th>
    </tr>
    </thead>
    <tbody>
        @foreach($lease_expense_annexure as $item)
            @php
                $total_payment = 0;
            @endphp
            <tr>
                <td>{{ \Carbon\Carbon::parse($item->date)->format('Y') }}</td>
                <td>{{ \Carbon\Carbon::parse($item->date)->format(config('settings.date_format')) }}</td>

                @foreach($item->payments_details as $key=>$detail)
                    @if($key != 'termination_penalty' && $key != 'residual_value')
                        <td>{{ $detail['payment_amount'] }}</td>
                        @php
                            $total_payment += $detail['payment_amount'];
                        @endphp
                    @endif
                @endforeach

                @if(isset($item->payments_details['termination_penalty']))
                    <td>{{ $item->payments_details['termination_penalty']['payment_amount'] }}</td>
                    @php
                        $total_payment += $item->payments_details['termination_penalty']['payment_amount'];
                    @endphp
                @else
                    <td>-</td>
                @endif

                @if(isset($item->payments_details['residual_value']))
                    <td>{{ $item->payments_details['residual_value']['payment_amount'] }}</td>
                    @php
                        $total_payment += $item->payments_details['residual_value']['payment_amount'];
                    @endphp
                @else
                    <td>-</td>
                @endif

                <td>{{$total_payment}}</td>
                <td>{{$item->opening_prepaid_payable_balance}}</td>
                @foreach($item->payments_details as $key=>$detail)
                    @if($key != 'termination_penalty' && $key != 'residual_value')
                        <td> {{ $detail['computed_lease_payment_expense'] }}</td>
                    @endif
                @endforeach

                @if(isset($item->payments_details['termination_penalty']))
                    <td>{{ $item->payments_details['termination_penalty']['computed_lease_payment_expense'] }}</td>
                @else
                    <td>-</td>
                @endif

                @if(isset($item->payments_details['residual_value']))
                    <td>{{ $item->payments_details['residual_value']['computed_lease_payment_expense'] }}</td>
                @else
                    <td>-</td>
                @endif

                <td>{{$item->total_computed_lease_expense}}</td>
                <td>{{$item->closing_prepaid_payable_balance}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
