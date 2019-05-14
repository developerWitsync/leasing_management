<div class="modal-header">
    <h5 class="modal-title">Historical Present Value Of Lease Liability Calculus</h5>
    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
<div class="modal-body">
    <div class="row" >
        <div class="col-md-12">
            <table class="table table-bordered table-striped table-condensed">
                <thead>
                    <th style="text-align: center"><strong>Year</strong></th>
                    <th style="text-align: center"><strong>Date of <br/>Lease Payments</strong></th>
                    <th style="text-align: center"><strong>Lease <br/> Payments</strong></th>
                    <th style="text-align: center"><strong>Historical Present<br/> Value of Lease Payments</strong></th>
                    <th style="text-align: center"><strong>Historical Present <br/>Value of Lease Liability</strong></th>

                    <th style="text-align: center"><strong>Historical Value of<br/> Lease Asset</strong></th>
                    <th style="text-align: center"><strong>Historical Depreciation</strong></th>
                    <th style="text-align: center"><strong>Historical Accumulated <br/>Depreciation</strong></th>
                    <th style="text-align: center"><strong>Carrying Value <br/>Of Lease Asset</strong></th>
                </thead>
                <tbody>
                    @foreach($annexure as $key=>$detail)
                        <tr>
                            <td>{{$detail->year}}</td>
                            <td>{{\Carbon\Carbon::parse($detail->date)->format(config('settings.date_format'))}}</td>

                            <td class="blueClr" align="center" style="font-weight: 600">{{$detail->payment_amount}}</td>
                            <td class="blueClr" align="center" style="font-weight: 600">{{$detail->present_value_of_lease_payment}}</td>
                            <td align="center" style="font-weight: 600">
                                @if($key == 0)
                                    {{$asset->historical_present_value_of_lease_liability}}
                                @else
                                    &nbsp;
                                @endif
                            </td>

                            <td class="blueClr" align="center" style="font-weight: 600">
                                @if($key == 0)
                                    {{$asset->historical_present_value_of_lease_liability}}
                                @else
                                    &nbsp;
                                @endif
                            </td>

                            <td class="blueClr" align="center" style="font-weight: 600">
                                @if(\Carbon\Carbon::parse($detail->date)->isLastOfMonth())
                                    {{$detail->historical_depreciation}}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="blueClr" align="center" style="font-weight: 600">
                                @if(\Carbon\Carbon::parse($detail->date)->isLastOfMonth())
                                    {{$detail->historical_accumulated_depreciation}}
                                @else
                                    -
                                @endif
                            </td>

                            <td class="blueClr" align="center" style="font-weight: 600">
                                @if(\Carbon\Carbon::parse($detail->date)->isLastOfMonth())
                                    {{$detail->carrying_value_of_lease_asset}}
                                @else
                                    -
                                @endif
                            </td>

                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
<div class="modal-footer">
    &nbsp;
</div>
