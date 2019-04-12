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