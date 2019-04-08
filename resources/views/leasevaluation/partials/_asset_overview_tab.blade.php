<div class="tabBxOuter" style="display: block;" id="assetTab1">
    <div class="leaseRafBx">
        <div class="leaserefouter clearfix">
            <div class="leaseref_left">
                <span>Lease Reference Number</span>
                <strong>{{$asset->uuid}}</strong>
            </div>
            <div class="leaseref_left" style="width: 30%">
                <span>Valuation Basis</span>
                <strong>
                    @if($subsequent_modified)
                        <i>
                            <label>Subsequent</label>
                            <label>Effective From
                                : {{ \Carbon\Carbon::parse($subsequent->effective_from)->format(config('settings.date_format')) }}</label>
                        </i>
                    @else
                        <i>
                            <label>Initial</label>
                        </i>
                    @endif
                </strong>
            </div>
        </div>
        <div class="lessernameTop clearfix">
            <div class="leasernameBx1 lesserName">
                <span>Lessor Name</span>
                <strong>{{$lease->lessor_name}}</strong>
            </div>
            <div class="leasernameBx2 lesserName">
                <span>Lease Classification</span>
                <strong>{{ $lease->leaseType->title }}</strong>
            </div>
            <div class="leasernameBx2 lesserName">
                <span>Lease Currency (LC)</span>
                <strong>{{ $lease->lease_contract_id }}</strong>
            </div>
            <div class="leasernameBx2 lesserName">
                <span>Units of Similar Characteristics</span>
                <strong>{{$asset->similar_asset_items}}</strong>
            </div>
            <div class="leasernameBx2 lesserName">
                <span>Lease Contract Start Date</span>
                <strong>{{ \Carbon\Carbon::parse($asset->lease_start_date)->format(config('settings.date_format')) }}</strong>
            </div>
        </div>
        <div class="clearfix">
            <div class="leasernameBx1 lesserName">
                <span>Initial Lease Free Period</span>
                <strong>{{ $asset->lease_free_period }} days</strong>
            </div>
            <div class="leasernameBx2 lesserName">
                <span>Effective Lease Start Date</span>
                <strong>{{ \Carbon\Carbon::parse($asset->accural_period)->format(config('settings.date_format')) }}</strong>
            </div>
            <div class="leasernameBx2 lesserName">
                <span>Lease Contract End Date</span>
                <strong>{{ \Carbon\Carbon::parse($asset->lease_end_date)->format(config('settings.date_format')) }}</strong>
            </div>
            {{--
            <div class="leasernameBx2 lesserName">--}}
            {{--<span>Expected Lease End Date</span>--}}
            {{--<strong>28-3-2019</strong>--}}
            {{--
         </div>
         --}}
            <div class="leasernameBx2 lesserName">
                <span>Lease Term</span>
                <strong>{{ $asset->lease_term }}</strong>
            </div>
        </div>
    </div>
    <!--Lease Locations-->
    <div class="locatPurposeOutBx">
        <div class="locatpurposeTop clearfix">
            <div class="locatpurposeBx">Location</div>
            <div class="locatpurposeBx1">Purpose</div>
            <div class="locatpurposeBx1">Expected Useful Life</div>
            <div class="locatpurposeBx1">Leases Prior to 2019</div>
            <div class="locatpurposeBx1">Lessor Invoice</div>
        </div>
        <div class="locatpurposeBot clearfix">
            <div class="leasernameBx1 leaserLocation">
                <span>Country : <strong>{{ $asset->country->name }}</strong></span>
                <span>Location : <strong>{{ $asset->location }}</strong></span>
            </div>
            <div class="leasernameBx2 leaserLocation">
                <span>Own / Sub-Lease : <strong>{{ $asset->specificUse->title }}</strong></span>
                @if($asset->use_of_asset)<span>Reasons : <strong>{{ $asset->use_of_asset }}</strong></span>@endif
            </div>
            <div class="leasernameBx2 leaserLocation">
                <span>Useful Life : <strong>
                        @if($asset->expectedLife->years == '-1')
                            Indefinite
                        @else
                            {{$asset->expectedLife->years}} years
                        @endif
                    </strong></span>
            </div>
            @if(\Carbon\Carbon::parse($asset->accural_period)->lessThan(\Carbon\Carbon::parse(getParentDetails()->accountingStandard->base_date)))
                <div class="leasernameBx2 leaseprior">
                    <span>Accounting Treatment:<strong>{{ $asset->accountingTreatment->title }}</strong></span>
                    <span>Lease Payment Basis:
                        <strong>
                            @if($asset->using_lease_payment == '1')
                                Current Lease Payment Effective From 2019
                            @else
                                Initial Lease Payment as on First Lease Start
                            @endif
                        </strong>
                    </span>
                </div>
            @endif
            <div class="leasernameBx2 leaserLocation">
                <span>Lessor Invoice :
                    <strong>
                        {{ strtoupper($lease->leasePaymentInvoice->lease_payment_invoice_received)}}
                    </strong>
                </span>
            </div>
        </div>
    </div>
    <!--Lease Termination-->
    <div class="locatPurposeOutBx">
        <div class="locatpurposeTop leaseterminatHd">
            Lease Termination Option
        </div>
        <div class="locatpurposeBot clearfix">
            <div class="leasernameBx1 leaseprior">
                <span>Termination Option Available Under the Contract
                <strong>Yes</strong>
            </span>
            </div>
                <div class="leasernameBx2 leaseprior">
                <span>Reasonable Certainity to Exercise Termination Option as of today
                    <strong>Yes</strong>
                </span>
            </div>
            <div class="leasernameBx2 leaseprior">
                <span>Expected Lease End Date
                    <strong>20-4-2019</strong>
                </span>
            </div>
            <div class="leasernameBx2 leaseprior">
                <span>Currency
                    <strong>USD</strong>
                </span>
            </div>
            <div class="leasernameBx2 leaseprior">
                <span>Termination Penalty
                    <strong>$250.00</strong>
                </span>
            </div>
        </div>
    </div>
    <!--Lease Renewal-->
    <div class="locatPurposeOutBx">
        <div class="locatpurposeTop leaseterminatHd">
            Lease Renewal Option
        </div>
        <div class="locatpurposeBot clearfix">
            <div class="leaserrenewalBx1 leaseprior">
            <span>Renewal Option Available Under the Contract
            <strong>Yes</strong>
            </span>
            </div>
            <div class="leaserrenewalBx2 leaseprior">
            <span>Reasonable Certainity to Exercise Termination Option as of today
            <strong>Yes</strong>
            </span>
            </div>
            <div class="leaserrenewalBx2 leaseprior">
            <span>Expected Lease End Date
            <strong>20-4-2019</strong>
            </span>
            </div>
        </div>
    </div>
    <!--Lease Purchase-->
    <div class="locatPurposeOutBx">
        <div class="locatpurposeTop leaseterminatHd">
            Lease Purchase Option
        </div>
        <div class="locatpurposeBot clearfix">
            <div class="leaserrenewalBx1 leaseprior">
            <span>Purchase Option Available Under the Contract
            <strong>Yes</strong>
            </span>
                <span>Expected Lease End Date
            <strong>20-5-2019</strong>
            </span>
            </div>
            <div class="leaserrenewalBx2 leaseprior">
            <span>Reasonable Certainity to Exercise Purchase Option as of today
            <strong>Yes</strong>
            </span>
                <span>Currency
            <strong>USD</strong>
            </span>
            </div>
            <div class="leaserrenewalBx2 leaseprior">
            <span>Expected Purchase Date
            <strong>20-4-2019</strong>
            </span>
                <span>Purchase Price
            <strong>$250.00</strong>
            </span>
            </div>
        </div>
    </div>
    <!--Lease Purchase-->
    <div class="locatPurposeOutBx">
        <div class="locatpurposeTop leaseterminatHd">
            Lease Purchase Option
        </div>
        <div class="locatpurposeBot clearfix">
            <div class="leaserrenewalBx1 leaseprior">
            <span>Purchase Option Available Under the Contract
            <strong>Yes</strong>
            </span>
                <span>Expected Lease End Date
            <strong>20-5-2019</strong>
            </span>
            </div>
            <div class="leaserrenewalBx2 leaseprior">
            <span>Reasonable Certainity to Exercise Purchase Option as of today
            <strong>Yes</strong>
            </span>
                <span>Currency
            <strong>USD</strong>
            </span>
            </div>
            <div class="leaserrenewalBx2 leaseprior">
            <span>Expected Purchase Date
            <strong>20-4-2019</strong>
            </span>
                <span>Purchase Price
            <strong>$250.00</strong>
            </span>
            </div>
        </div>
    </div>
    <!--Lease Payments-->
    <div class="locatPurposeOutBx">
        <div class="locatpurposeTop leaseterminatHd">
            Lease Payments
        </div>
        <div class="leasepaymentTble">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <th width="16.66%">Lease Payments</th>
                    <th width="16.66%">Type</th>
                    <th width="16.66%">Nature</th>
                    <th width="16.66%">LP Interval</th>
                    <th width="16.66%">Interval Point</th>
                    <th width="16.66%">First Lease Payment <br/>Start Date</th>
                </tr>
                <tr>
                    <td>Basic Rent</td>
                    <td>Lease <br/> Component</td>
                    <td>Fixed Lease <br/> Payment</td>
                    <td>Monthly</td>
                    <td>At Lease <br/> Interval End</td>
                    <td>Date</td>
                </tr>
                <tr>
                    <td>Basic Rent</td>
                    <td>Lease <br/> Component</td>
                    <td>Fixed Lease <br/> Payment</td>
                    <td>Monthly</td>
                    <td>At Lease <br/> Interval End</td>
                    <td>Date</td>
                </tr>
            </table>
        </div>
    </div>
    <!--Lease Payments-->
    <div class="locatPurposeOutBx">
        <div class="locatpurposeTop leaseterminatHd">
            Lease Escalation
        </div>
        <div class="leasepaymentTble">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <th width="16.66%">Lease Payments</th>
                    <th width="16.66%">Lease Escalation Applied</th>
                    <th width="16.66%">Escalation Effective Date</th>
                    <th width="16.66%">Escalation Basis</th>
                    <th width="16.66%">Escalation Consitently<br/>Applied</th>
                    <th width="16.66%">Escalation <br/> Fixed Rate</th>
                </tr>
                <tr>
                    <td>Basic Rent</td>
                    <td>Yes</td>
                    <td>20-05-2019</td>
                    <td>10% of $250</td>
                    <td>Yes</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>Basic Rent</td>
                    <td>Yes</td>
                    <td>20-05-2019</td>
                    <td>10% of $250</td>
                    <td>Yes</td>
                    <td>10%</td>
                </tr>
                <tr>
                    <td>Basic Rent</td>
                    <td>Yes</td>
                    <td>20-05-2019</td>
                    <td>10% of $250</td>
                    <td>Yes</td>
                    <td>10%</td>
                </tr>
            </table>
        </div>
    </div>
    <!--Residual Value Guarantee-->
    <div class="locatPurposeOutBx">
        <div class="locatpurposeTop leaseterminatHd">
            Residual Value Guarantee
        </div>
        <div class="locatpurposeBot clearfix">
            <div class="leaserrenewalBx1 leaseprior">
            <span>Residual Value Guarantee Applicable
            <strong>Yes</strong>
            </span>
                <span>Currency
            <strong>USD</strong>
            </span>
            </div>
            <div class="leaserrenewalBx2 leaseprior">
            <span>Variable Basis
            <strong>Turnover Lease</strong>
            </span>
                <span>Residual Value
            <strong>123</strong>
            </span>
            </div>
            <div class="leaserrenewalBx2 leaseprior">
            <span>Determinable
            <strong>Yes</strong>
            </span>
                <span>Amount
            <strong>$250.00</strong>
            </span>
            </div>
        </div>
    </div>
    <!--Discounting Rate-->
    <div class="locatPurposeOutBx">
        <div class="locatpurposeTop leaseterminatHd">
            Discounting Rate
        </div>
        <div class="discountRateOuter">
            <div class="discountTop clearfix">
                <div class="discountBx">
                    <span>Implicit Interest Rate</span>
                    <strong>30%</strong>
                </div>
                <div class="discountBx">
                    <span>Annual Avg. Escalation Rate</span>
                    <strong>50%</strong>
                </div>
                <div class="discountBx">
                    <span>Discount Rate in Use</span>
                    <strong>10%</strong>
                </div>
                <div class="discountBx">
                    <span>Effective Daily Discount Rate</span>
                    <strong>20%</strong>
                </div>
            </div>
            <div class="graphBx"><img src="../assets/images/graph-img.png" alt=""></div>
        </div>
    </div>
    <!--Lease Security Deposit-->
    <div class="locatPurposeOutBx">
        <div class="locatpurposeTop leaseterminatHd">
            Lease Security Deposit
        </div>
        <div class="locatpurposeBot clearfix">
            <div class="leaserrenewalBx1 leaseprior">
            <span>Any Security Deposit
            <strong>Yes</strong>
            </span>
                <span>Type of Secuity
            <strong>Money Transfer</strong>
            </span>
            </div>
            <div class="leaserrenewalBx2 leaseprior">
            <span>Security Deposit Refundable <br/> or Adjustable ?
            <strong>Refundable</strong>
            </span>
                <span>Currency
            <strong>USD</strong>
            </span>
            </div>
            <div class="leaserrenewalBx2 leaseprior">
            <span>Date of Payment
            <strong>20-03-2019</strong>
            </span>
                <span>Value
            <strong>$250.00</strong>
            </span>
            </div>
        </div>
    </div>
</div>

