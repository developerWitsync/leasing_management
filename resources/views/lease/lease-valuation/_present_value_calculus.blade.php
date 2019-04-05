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
                            $key = array_keys($data);
                            $key = $key[0];
                            $allowed_payments = count($payments) + 1 + 3;
                        @endphp
                        <td rowspan="{{ $allowed_payments }}">
                            {{ $year }}
                        </td>
                    </tr>

                    @foreach($payments as $payment)
                        {{--@php--}}
                            {{--if($payment->paymentDueDates->count() == 0){--}}
                                {{--continue;--}}
                            {{--}--}}
                        {{--@endphp--}}
                        <tr>
                            <th>{{ $payment->name }}</th>
                            @php
                                $sub_total = 0;
                            @endphp
                            @foreach($months as $month)
                                @if(isset($data[$month]) && isset($data[$month]["payment_".$payment->id]))
                                    <td>{{ $data[$month]["payment_".$payment->id]->lease_liability }}</td>
                                    @php
                                        $sub_total = $sub_total + $data[$month]["payment_".$payment->id]->lease_liability;
                                    @endphp
                                @else
                                    <td>&nbsp;</td>
                                @endif
                            @endforeach
                            <td>{{$sub_total}}</td>
                            @php
                                $grand_total = $grand_total + $sub_total;
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <th>Termination</th>
                        @php
                            $sub_total = 0;
                        @endphp
                        @foreach($months as $month)
                            @if(isset($data[$month]['termination'][0]))
                                <td>{{ $data[$month]['termination'][0]->lease_liability }}</td>
                                @php
                                    $sub_total = $sub_total + $data[$month]['termination'][0]->lease_liability;
                                @endphp
                            @else
                                <td>&nbsp;</td>
                            @endif
                        @endforeach
                        <td>{{$sub_total}}</td>
                        @php
                            $grand_total = $grand_total + $sub_total;
                        @endphp
                    </tr>
                    <tr>
                        <th>Residual</th>
                        @php
                            $sub_total = 0;
                        @endphp
                        @foreach($months as $month)
                            @if(isset($data[$month]['residual']))
                                <td>{{ $data[$month]['residual'][0]->lease_liability }}</td>
                                @php
                                    $sub_total = $sub_total + $data[$month]['residual'][0]->lease_liability;
                                @endphp
                            @else
                                <td>&nbsp;</td>
                            @endif
                        @endforeach
                        <td>{{$sub_total}}</td>
                        @php
                            $grand_total = $grand_total + $sub_total;
                        @endphp
                    </tr>
                    <tr>
                        <th>Purchase</th>
                        @php
                            $sub_total = 0;
                        @endphp
                        @foreach($months as $month)
                            @if(isset($data[$month]['purchase']))
                                <td>{{ $data[$month]['purchase'][0]->lease_liability }}</td>
                                @php
                                    $sub_total = $sub_total + $data[$month]['purchase'][0]->lease_liability;
                                @endphp
                            @else
                                <td>&nbsp;</td>
                            @endif
                        @endforeach
                        <td>{{$sub_total}}</td>
                        @php
                            $grand_total = $grand_total + $sub_total;
                        @endphp
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
    <button type="button" data-dismiss="modal" class="btn btn-success">Confirm</button>
</div>
