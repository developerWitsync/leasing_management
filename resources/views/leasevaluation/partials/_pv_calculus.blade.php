<div class="modal-header">
    <h5 class="modal-title">Present Value Of Lease Liability Calculus</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="alert alert-danger">
        <strong>Note!</strong> The below information is generated on the basis of the current inputs.
    </div>



    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                <tr>
                    <th style="text-align: center"><strong>Year</strong></th>
                    <th style="text-align: center"><strong>Lease Payment<br/> Dates</strong></th>
                    @foreach($payments as $payment)
                        <th style="text-align: center"><strong>{{$payment['name']}}</strong></th>
                    @endforeach
                    <th style="text-align: center"><strong>Termination<br/> Penalty</strong></th>
                    <th style="text-align: center"><strong>Purchase Value</strong></th>
                    <th style="text-align: center"><strong>Residual Value</strong></th>
                    <th style="text-align: center"><strong>Total Undiscounted<br/> Value</strong></th>
                    <th style="text-align: center"><strong>Present Value of<br/> Lease Liability</strong></th>
                </tr>

                </thead>
                <tbody>
                @php
                    $payments_total = [];
                    $termination_total = 0;
                    $purchase_option_total = 0;
                    $residual_value_total = 0;
                    $total_undiscounted_liability = 0;
                    $total_present_value_liability = 0;
                @endphp
                @foreach(collect($liability_caclulus_data)->groupBy('date') as $key=>$data)
                    @php
                        $total_undiscounted_value = 0;
                        $total_present_value = 0;
                    @endphp
                    <tr>
                        <td class="" align="center">{{ \Carbon\Carbon::parse($key)->format('Y') }}</td>
                        <td class="" align="center">{{ \Carbon\Carbon::parse($key)->format('Y-m-d') }}</td>
                        @php
                            $payment_ids_array = collect($data)->pluck('payment_id')->toArray();
                        @endphp
                        @foreach($payments as $payment)
                            @if(in_array($payment['id'], $payment_ids_array))
                                @php
                                    $current_data = collect($data)->where('payment_id', '=', $payment['id'])->first();
                                    $total_undiscounted_value += $current_data['total_amount_payable'];
                                    $total_present_value += $current_data['lease_liability'];
                                    if(isset($payments_total[$payment['id']])) {
                                        $payments_total[$payment['id']] += $current_data['total_amount_payable'];
                                    } else {
                                        $payments_total[$payment['id']] = $current_data['total_amount_payable'];
                                    }
                                @endphp
                                <td class="blueClr" align="center" style="font-weight: 600">{{ $current_data['total_amount_payable'] }}</td>
                            @else
                                <td class="blueClr" align="center" style="font-weight: 600">-</td>
                            @endif
                        @endforeach

                        @if(collect($data)->where('payment_type', '=', 'termination_option')->isNotEmpty())
                            @php
                                $current_data = collect($data)->where('payment_type', '=', 'termination_option')->first();
                                $total_undiscounted_value += $current_data['total_amount_payable'];
                                $total_present_value += $current_data['lease_liability'];
                                $termination_total = $current_data['total_amount_payable'];
                            @endphp
                            <td class="blueClr" align="center" style="font-weight: 600">{{ $current_data['total_amount_payable'] }}</td>
                        @else
                            <td class="blueClr" align="center" style="font-weight: 600">-</td>
                        @endif

                        @if(collect($data)->where('payment_type', '=', 'purchase_option')->isNotEmpty())
                            @php
                                $current_data = collect($data)->where('payment_type', '=', 'purchase_option')->first();
                                $total_undiscounted_value += $current_data['total_amount_payable'];
                                $total_present_value += $current_data['lease_liability'];
                                $purchase_option_total = $current_data['total_amount_payable'];
                            @endphp
                            <td class="blueClr" align="center" style="font-weight: 600">{{ $current_data['total_amount_payable'] }}</td>
                        @else
                            <td class="blueClr" align="center" style="font-weight: 600">-</td>
                        @endif

                        @if(collect($data)->where('payment_type', '=', 'residual_value')->isNotEmpty())
                            @php
                                $current_data = collect($data)->where('payment_type', '=', 'residual_value')->first();
                                $total_undiscounted_value += $current_data['total_amount_payable'];
                                $total_present_value += $current_data['lease_liability'];
                                $residual_value_total = $current_data['total_amount_payable'];
                            @endphp
                            <td class="blueClr" align="center" style="font-weight: 600">{{ $current_data['total_amount_payable'] }}</td>
                        @else
                            <td class="blueClr" align="center" style="font-weight: 600">-</td>
                        @endif

                        <td class="blueClr" align="center" style="font-weight: 600">{{ $total_undiscounted_value }}</td>
                        <td class="blueClr" align="center" style="font-weight: 600">{{ $total_present_value }}</td>
                        @php
                            $total_undiscounted_liability += $total_undiscounted_value;
                            $total_present_value_liability += $total_present_value;
                        @endphp
                    </tr>
                @endforeach

                <tr>
                    <th colspan="2">Grand Total</th>
                    @foreach($payments as $payment)
                        @if(isset($payments_total[$payment['id']]))
                            <td class="blueClr" align="center" style="font-weight: 600">{{ $payments_total[$payment['id']] }}</td>
                        @else
                            <td class="blueClr" align="center" style="font-weight: 600">-</td>
                        @endif
                    @endforeach
                    <td class="blueClr" align="center" style="font-weight: 600">{{$termination_total}}</td>
                    <td class="blueClr" align="center" style="font-weight: 600">{{$purchase_option_total}}</td>
                    <td class="blueClr" align="center" style="font-weight: 600">{{$residual_value_total}}</td>
                    <td class="blueClr" align="center" style="font-weight: 600">{{$total_undiscounted_liability}}</td>
                    <td class="blueClr" align="center" style="font-weight: 600">{{$total_present_value_liability}}</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" data-dismiss="modal" class="btn btn-success">Confirm</button>
</div>
