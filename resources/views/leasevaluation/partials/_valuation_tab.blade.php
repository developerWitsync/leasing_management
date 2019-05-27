<div class="tabBxOuter" style="display: block;" id="assetTab2">
    <div class="leaseRafBx">
        <div class="initialGraphBx">
            {{--<img src="{{ asset('assets/images/initialGraph.png') }}">--}}
            <div class="row">
                <div class="col-md-3" style="padding-top: 30px;font-size: 18px; font-weight: 600;color: #3f4041">
                    Initial & Subsequent Lease Valuation Graph
                </div>
                <div class="col-md-9">
                    <div id="chart1">
                        <svg></svg>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if($asset->using_lease_payment)
        <div class="locatPurposeOutBx">
            <div class="locatpurposeTop leaseterminatHd">
                Valuation Method Selected - <small>
                    @if($asset->using_lease_payment == '2')
                        Modified Retrospective Approach ( By Adjusting Opening Equity)
                    @elseif($asset->using_lease_payment == '1')
                        Modified Retrospective Approach (Without Adjusting Opening Equity). Value Of Asset Will Be Equal To Present Value Of Lease Liability
                    @endif
                </small>
            </div>
            @if($asset->using_lease_payment == '2')
                <div class="leasepaymentTble">
                    <table cellpadding="0" cellspacing="0" width="100%" style="border-collapse: initial;">
                        <thead>
                            <th>Initial Value of Lease Asset</th>
                            <th>Initial Present Value of Lease Liability</th>
                            <th>Opening Prepaid Lease Balances</th>
                            <th>Accrued Lease Liability</th>
                            <th>Adjustment to Opening Equity</th>
                            <th>Historical Carrying Amount of Lease Asset</th>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="text-align: center">{{$valuation_method->initial_value_of_lease_asset}}</td>
                                <td style="text-align: center">{{$valuation_method->initial_present_value_of_lease_liability}}</td>
                                <td style="text-align: center">{{$valuation_method->initial_prepaid_lease_payments}}</td>
                                <td style="text-align: center"> {{$valuation_method->accrued_lease_payment_balance}}</td>
                                <td style="text-align: center"> {{$valuation_method->adjustment_to_opening_equity}}</td>
                                <td style="text-align: center">
                                    <a href="javascript:void(0);" class="btn btn-xs btn-primary carrying_amount_annexure" data-id="{{$asset->lease->id}}">Carrying Amount Annexure</a>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    @endif

    <!--Lease Valuation-->
    <div class="locatPurposeOutBx">
        <div class="locatpurposeTop leaseterminatHd">
            Lease Valuation
        </div>
        <div class="leasepaymentTble">
            <table cellpadding="0" cellspacing="0" width="100%" id="complete_lease_valuations" style="border-collapse: initial;">
                <thead>
                @if(!$show_statutory_columns)
                    <tr>
                        <th colspan="4">&nbsp;</th>
                        <th colspan="6" style="text-align: center;border-bottom: 1px solid #ccc ">Lease Currency - ({{$lease->lease_contract_id}})</th>
                    </tr>
                    <tr>
                        <th>S No.</th>
                        <th>Effective Date</th>
                        <th>Valuation Type</th>
                        <th>Discount Rate Applied %</th>

                        <th>UD Lease Liability</th>
                        <th>PV of Lease Liability</th>
                        <th>Value of Lease Asset</th>
                        <th>Fair Value</th>
                        <th>Impairement</th>
                        <th>Action</th>
                    </tr>
                @else
                    <tr>
                        <th colspan="4">&nbsp;</th>
                        <th colspan="5" style="text-align: center;border-bottom: 1px solid #ccc ">Lease Currency - ({{$lease->lease_contract_id}})</th>
                        <th colspan="7" style="text-align: center;border-bottom: 1px solid #ccc ">Statutory Currency - ({{$statutory_currency}})</th>
                    </tr>
                    <tr>
                        <th>S No.</th>
                        <th>Effective Date</th>
                        <th>Valuation Type</th>
                        <th>Discount Rate Applied %</th>

                        <th>UD Lease Liability</th>
                        <th>PV of Lease Liability</th>
                        <th>Value of Lease Asset</th>
                        <th>Fair Value</th>
                        <th>Impairement</th>

                        <th>Exchange Rate</th>
                        <th>UD Lease Liability</th>
                        <th>PV of Lease Liability</th>
                        <th>Value of Lease Asset</th>
                        <th>Fair Value</th>
                        <th>Impairement</th>
                        <th>Action</th>
                    </tr>
                @endif
                </thead>
            </table>
        </div>
    </div>

</div>