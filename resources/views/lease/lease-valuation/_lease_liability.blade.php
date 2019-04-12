<div class="panel panel-info">
    <div class="panel-heading">Present Value of Lease Liability</div>
    <table class="table table-bordered table-responsive">

        <tr>
            <th>&nbsp;</th>
            <th style="text-align: center">Undiscounted Value</th>
            <th style="text-align: center">Present Value</th>
        </tr>

        @php
            $total = 0;
        @endphp

        @foreach($payments as $payment)
            <tr>
                <th style="text-align: center">{{ $payment->name }}</th>
                <td style="border: 1px solid #ddd;text-align: center;">{{ $payment->getUndiscountedValue() }}</td>
                <td style="border: 1px solid #ddd;text-align: center;" class="load_payment_present_value" data-payment_id="{{$payment->id}}" data-asset_id="{{$asset->id}}"></td>
                @php
                    $total = $total + $payment->undiscounted_liability_value;
                @endphp
            </tr>
        @endforeach

        @if($asset->terminationOption->lease_termination_option_available == "yes" && $asset->terminationOption->exercise_termination_option_available == "yes" && $asset->terminationOption->termination_penalty_applicable == "yes")
            <tr>
                <th style="text-align: center">Termination Penalty</th>
                <td style="border: 1px solid #ddd;text-align: center;">{{ $asset->terminationOption->termination_penalty }}</td>
                <td style="border: 1px solid #ddd;text-align: center;" class="load_termination_present_value" data-asset_id="{{$asset->id}}"></td>
                @php
                    $total = $total + $asset->terminationOption->termination_penalty;
                @endphp
            </tr>
        @endif

        @if($asset->residualGuranteeValue->any_residual_value_gurantee == "yes")
            <tr>
                <th style="text-align: center">Residual Value Guarantee</th>
                <td style="border: 1px solid #ddd;text-align: center;">{{ $asset->residualGuranteeValue->residual_gurantee_value }}</td>
                <td style="border: 1px solid #ddd;text-align: center;" class="load_residual_present_value" data-asset_id="{{$asset->id}}"></td>
                @php
                    $total = $total + $asset->residualGuranteeValue->residual_gurantee_value;
                @endphp
            </tr>
        @endif

        @if($asset->purchaseOption && $asset->purchaseOption->purchase_option_clause == "yes" && $asset->purchaseOption->purchase_option_exerecisable == "yes")
            <tr>
                <th style="text-align: center">Purchase Value</th>
                <td style="border: 1px solid #ddd;text-align: center;">{{ $asset->purchaseOption->purchase_price }}</td>
                <td style="border: 1px solid #ddd;text-align: center;" class="load_purchase_present_value" data-asset_id="{{$asset->id}}"></td>
            </tr>
            @php
                $total = $total + $asset->purchaseOption->purchase_price;
            @endphp
        @endif

        <tr>
            <th style="text-align: center">Total Value of Lease Liability</th>
            <td style="border: 1px solid #ddd;text-align: center;">{{ $total }}</td>
            <td>
                <span class="load_lease_liability" data-asset_id="{{ $asset->id }}"></span>
                <a class="btn btn-sm btn-primary" href="javascript:void(0);"
                   onclick="javascript:showPresentValueCalculus('{{ $asset->id }}');">PV Calculus</a>
                <i class="fa fa-spinner fa-spin calculus_spinner_{{$asset->id}}"
                   style="font-size:24px;display: none;"></i>
            </td>
        </tr>

    </table>
</div>