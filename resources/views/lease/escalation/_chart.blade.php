<div class="modal-header">
    <h5 class="modal-title">Escalations Chart</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">

    @if (!empty($errors))
        <div class="alert alert-danger">
            <ul>
                @foreach (array_unique($errors->all()) as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @else
        <div class="alert alert-danger">
            <strong>Note!</strong> The below information is generated on the basis of the current inputs.
        </div>

        <div class="row">
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
                                <td>
                                    <strong>
                                        {{ $year }}
                                    </strong>
                                </td>
                                @php
                                    $total = 0;
                                @endphp
                                @foreach($months as $month)
                                    @if(isset($escalations[$year][$month]) && !empty($escalations[$year][$month]))
                                        <td class="{{ $escalations[$year][$month]['current_class'] }}">
                                            <span>
                                                @if($requestData['is_escalation_applicable'] == 'yes')
                                                    {{ $escalations[$year][$month]['percentage'] }} @if($requestData['escalation_basis'] == '1') % @endif / {{ $escalations[$year][$month]['amount'] }}
                                                @elseif($requestData['is_escalation_applicable'] == 'no')
                                                    {{ $escalations[$year][$month]['amount'] }}
                                                @endif
                                            </span>
                                            @php
                                                $total = $total + $escalations[$year][$month]['amount'];
                                            @endphp
                                        </td>
                                    @else
                                        <td class="info">&nbsp;</td>
                                    @endif
                                @endforeach
                                <td>{{ $total }}</td>
                                @php
                                    $grand_total += $total;
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

    @endif

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-success close" data-dismiss="modal" aria-label="Close">Confirm</button>

    <!-- <button type="button" class="close" >
        <span aria-hidden="true">×</span>
    </button> -->
</div>
