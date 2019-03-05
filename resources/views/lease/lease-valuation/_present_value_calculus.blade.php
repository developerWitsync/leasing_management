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

    <div class="row" style="width: 2000px;">
        <div class="col-md-12">
            <table class="table table-bordered">
                <thead>
                <tr>
                    <th>&nbsp;</th>
                    <th>&nbsp;</th>
                    @foreach($months as $month)
                        <th>{{ $month }}</th>
                    @endforeach
                    <th>Total</th>
                </tr>

                </thead>
                <tbody>
                @php
                    $grand_total = 0;
                @endphp
                @foreach($liability_caclulus_data as $year=>$data)
                    <tr>
                        @php
                            $rowspan = 1;
                            $allowed_payments = count($data['Jan']) + 1 + 3;
                        @endphp
                        <td rowspan="{{ $allowed_payments }}">
                            {{ $year }}
                        </td>
                    </tr>

                    @foreach($payments as $payment)
                        <tr>
                            <th>{{ $payment->name }}</th>
                            @php
                                $sub_total = 0;
                            @endphp
                            @foreach($months as $month)
                                <td>{{ $data[$month]["payment_".$payment->id][0]->lease_liability }}</td>
                                @php
                                    $sub_total = $sub_total + $data[$month]["payment_".$payment->id][0]->lease_liability;
                                @endphp
                            @endforeach
                            <td>{{$sub_total}}</td>
                            @php
                                $grand_total = $grand_total + $sub_total;
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <th>Termination</th>
                        @foreach($payments as $payment)
                            @foreach($months as $month)
                                <td>{{ $data[$month]["payment_".$payment->id][0]->termination_penalty }}</td>
                            @endforeach
                            @php
                                break;
                            @endphp

                        @endforeach
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Residual</th>
                        @foreach($payments as $payment)
                            @foreach($months as $month)
                                <td>{{ $data[$month]["payment_".$payment->id][0]->residual_value_gurantee_value }}</td>
                            @endforeach
                            @php
                                break;
                            @endphp
                        @endforeach
                        <td>&nbsp;</td>
                    </tr>
                    <tr>
                        <th>Purchase</th>
                        @foreach($payments as $payment)
                            @foreach($months as $month)
                                <td>{{ $data[$month]["payment_".$payment->id][0]->purchase_option_price }}</td>
                            @endforeach
                            @php
                                break;
                            @endphp
                        @endforeach
                        <td>&nbsp;</td>
                    </tr>
                @endforeach
                <tr>
                    <th colspan="14">Grand Total</th>
                    <td>{{ $grand_total }}</td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-success">Confirm</button>
</div>
