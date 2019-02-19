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
                    @foreach($years as $year)
                        <tr>
                            <td width="100px">
                                <strong>
                                    {{ $year }}
                                </strong>
                            </td>
                            @php
                                $year_total = 0;
                            @endphp
                            @foreach($months as $month)
                                @if(isset($liability_caclulus_data[$year][$month]) && !empty($liability_caclulus_data[$year][$month]))
                                    <td>
                                        {{--{{ json_encode($liability_caclulus_data[$year][$month]) }}--}}
                                        <table cellspacing="0" cellpadding="0" border="1" width="100%" class="tableInnData">
                                            <thead>
                                                @foreach($liability_caclulus_data[$year][$month] as $key=>$current_data)
                                                    <th>{{ $current_data[0]->payment_name }}</th>
                                                @endforeach
                                                <th>Total</th>
                                            </thead>
                                            <tr>
                                                @php
                                                    $month_total = 0;
                                                @endphp
                                                @foreach($liability_caclulus_data[$year][$month] as $key=>$current_data)
                                                    <td>{{ round($current_data[0]->lease_liability,2)}}</td>
                                                    @php
                                                        $month_total = $month_total + $current_data[0]->lease_liability;
                                                    @endphp
                                                @endforeach
                                                <td>{{ number_format($month_total, 2)}}  </td>
                                                @php
                                                    $year_total = $year_total + $month_total;
                                                @endphp
                                            </tr>
                                        </table>
                                    </td>
                                @else
                                    <td class="info">&nbsp;</td>
                                @endif
                            @endforeach
                            <td>{{ $year_total }}</td>
                            @php
                                $grand_total = $grand_total + $year_total;
                            @endphp
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="13">TOTAL</td>
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
