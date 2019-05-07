<table cellpadding="0" cellspacing="0" border="0" style="width: 2000px;">
    <thead>
    <tr>

        <th width="5%" rowspan="2">Year</th>
        <th width="10%" rowspan="2">
            <span style="border-bottom:2px dashed #666; padding: 3px 0; display: block;">Lease Start Date</span>
            <span style="border-bottom:2px dashed #666; padding: 3px 0; display: block;">Lease Payment Dates</span>
            <span>Month End Dates</span>
        </th>
        {{--<th rowspan="2">#Days</th>
        <th rowspan="2">Discount <br/>Rate</th>--}}

        <th colspan="9" style="text-align: center">
            <span style="text-align: center; border-bottom: #cccfd9 solid 1px; display: block; padding-bottom: 5px;">Lease Currency - Specify Currency</span>
        </th>
    </tr>
    <tr>
        <th>Opening <br/> Lease Liability</th>
        <th>Monthly <br/> Interest Expense</th>
        <th>Lease <br/> Payments</th>
        <th>Closing <br/> Lease Liability</th>

        <th>Value <br/> Of Lease Asset</th>
        <th>Subsequent <br/>Increase/Decrease</th>
        <th>Depreciation</th>
        <th>Accumulated <br/>Depreciation</th>
        <th>Carrying Value <br/>Of Lease Asset</th>
    </tr>
    </thead>
    @php
        $i = 1;
        $effective_date = null;
    @endphp
    @foreach($interest_depreciation as $modify_id=>$details)
        <tr bgcolor="#117bb8" class="categoryTble">

            <td style="color: #fff;font-size: 14px;">Part {{$i}}:</td>

            @if($modify_id == "")
                <td colspan="12" style="color: #fff;font-size: 16px;">
                    Initial Valuation Basis
                </td>
            @else
                <td colspan="3" style="color: #fff;font-size: 16px;">Subsequent Lease Valuation</td>
                <td colspan="2" style="color: #fff;font-size: 16px;">Subsequent Reference# {{$i - 1}}</td>
                <td colspan="1" style="color: #fff;font-size: 16px;">Effective from</td>
                <td colspan="1" style="color: #fff;font-size: 16px;">{{ \Carbon\Carbon::parse($effective_date)->addDay(1)->format(config('settings.date_format')) }}</td>
                <td colspan="5" style="color: #fff;font-size: 16px;">&nbsp;</td>
            @endif
            @php
                $show_value_of_lease_asset = true;
            @endphp
        </tr>
        @foreach($details as $key=>$detail)
            <tr>
                <td>{{\Carbon\Carbon::parse($detail->date)->format('Y')}}</td>
                <td>{{\Carbon\Carbon::parse($detail->date)->format(config('settings.date_format'))}}</td>

                <td class="blueClr" align="center" style="font-weight: 600">{{$detail->opening_lease_liability}}</td>
                <td class="blueClr" align="center" style="font-weight: 600">{{$detail->interest_expense}}</td>
                <td align="center" style="font-weight: 600">{{$detail->lease_payment}}</td>
                <td class="blueClr" align="center" style="font-weight: 600">{{ $detail->closing_lease_liability }}</td>
                @if($show_value_of_lease_asset)
                    <td class="blueClr" align="center" style="font-weight: 600">{{ $detail->value_of_lease_asset }}</td>
                    <td class="blueClr" align="center" style="font-weight: 600">  {{ $detail->change }} </td>
                @else
                    <td class="blueClr" align="center" style="font-weight: 600">&nbsp;</td>
                    <td class="blueClr" align="center" style="font-weight: 600">&nbsp;</td>
                @endif
                @if(\Carbon\Carbon::parse($detail->date)->isLastOfMonth())
                    <td class="blueClr" align="center" style="font-weight: 600">{{ $detail->depreciation }}</td>
                    <td class="blueClr" align="center" style="font-weight: 600">{{ $detail->accumulated_depreciation }}</td>
                    <td class="blueClr" align="center" style="font-weight: 600">{{ $detail->carrying_value_of_lease_asset }}</td>
                @else
                    <td class="blueClr" align="center" style="font-weight: 600"> -</td>
                    <td class="blueClr" align="center" style="font-weight: 600"> -</td>
                    <td class="blueClr" align="center" style="font-weight: 600"> -</td>
                    <td class="blueClr" align="center" style="font-weight: 600"> -</td>
                    <td class="blueClr" align="center" style="font-weight: 600"> -</td>
            @endif
                @php
                    $show_value_of_lease_asset = false;
                    $effective_date = $detail->date;
                @endphp
            </tr>
        @endforeach
        @php
            $i += 1;
        @endphp
    @endforeach
</table>
