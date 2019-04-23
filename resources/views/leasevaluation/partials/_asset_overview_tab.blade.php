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
            @else
                <div class="leasernameBx2 leaseprior">
                    &nbsp;
                </div>
            @endif

                <div class="leasernameBx2 leaserLocation">
                    <span>Lessor Invoice :
                        <strong>
                            @if($lease->leasePaymentInvoice)
                                {{ strtoupper($lease->leasePaymentInvoice->lease_payment_invoice_received) }}
                            @else
                                N/A
                            @endif
                        </strong>
                    </span>
                </div>
        </div>
    </div>

@if($asset->terminationOption->lease_termination_option_available == "yes")
    <!--Lease Termination-->
        <div class="locatPurposeOutBx">
            <div class="locatpurposeTop leaseterminatHd">
                Lease Termination Option
            </div>
            <div class="locatpurposeBot clearfix">

                <div class="leasernameBx1 leaseprior">
                <span>Termination Option Available Under the Contract
                    <strong>{{ ucfirst($asset->terminationOption->lease_termination_option_available) }}</strong>
                </span>
                </div>

                <div class="leasernameBx2 leaseprior">
                    <span>Reasonable Certainity to Exercise Termination Option as of today
                    <strong>{{ ucfirst($asset->terminationOption->exercise_termination_option_available) }}</strong>
                </span>
                </div>

                <div class="leasernameBx2 leaseprior">
                    <span>Termination Penalty Applicable
                    <strong>{{ ucfirst($asset->terminationOption->termination_penalty_applicable) }}</strong>
                </span>
                </div>

                <div class="leasernameBx2 leaseprior">
                <span>Expected Lease End Date
                    <strong>
                        @if($asset->terminationOption->lease_termination_option_available == "yes" && $asset->terminationOption->exercise_termination_option_available == "yes")
                            {{ \Carbon\Carbon::parse($asset->terminationOption->lease_end_date)->format(config('settings.date_format')) }}
                        @else
                            -
                        @endif
                    </strong>
                </span>
                </div>

                @if($asset->terminationOption->lease_termination_option_available == "yes" && $asset->terminationOption->exercise_termination_option_available == "yes" && $asset->terminationOption->termination_penalty_applicable == "yes")
                    <div class="leasernameBx2 leaseprior">
                    <span>Currency
                        <strong>{{ $asset->terminationOption->currency }}</strong>
                    </span>
                    </div>
                    <div class="leasernameBx2 leaseprior">
                    <span>Termination Penalty
                        <strong>{{ number_format($asset->terminationOption->termination_penalty, 2) }}</strong>
                    </span>
                    </div>
                @endif
            </div>
        </div>
@endif
<!--Lease Renewal-->
    @if($asset->terminationOption->lease_termination_option_available == "no" || $asset->terminationOption->exercise_termination_option_available == "no")
        <div class="locatPurposeOutBx">
            <div class="locatpurposeTop leaseterminatHd">
                Lease Renewal Option
            </div>
            <div class="locatpurposeBot clearfix">
                <div class="leaserrenewalBx1 leaseprior">
                <span>Renewal Option Available Under the Contract
                <strong>{{ ucfirst($asset->renewableOptionValue->is_renewal_option_under_contract) }}</strong>
                </span>
                </div>
                <div class="leaserrenewalBx2 leaseprior">
                <span>Reasonable Certainity to Exercise Termination Option as of today
                    @if($asset->renewableOptionValue->is_reasonable_certainity_option)
                        <strong>{{ucfirst($asset->renewableOptionValue->is_reasonable_certainity_option)}}</strong>
                    @else
                        <strong> - </strong>
                    @endif
                </span>
                </div>

                <div class="leaserrenewalBx2 leaseprior">
                    <span>Expected Lease End Date
                        @if($asset->renewableOptionValue->is_renewal_option_under_contract == 'yes' && $asset->renewableOptionValue->is_reasonable_certainity_option == 'yes')
                            <strong>{{ \Carbon\Carbon::parse($asset->renewableOptionValue->expected_lease_end_Date)->format(config('settings.date_format')) }}</strong>
                        @else
                            <strong> - </strong>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    @endif

    @if($asset->terminationOption->lease_termination_option_available == "no" || $asset->terminationOption->exercise_termination_option_available == "no")
    <!--Lease Purchase-->
        <div class="locatPurposeOutBx">
            <div class="locatpurposeTop leaseterminatHd">
                Lease Purchase Option
            </div>
            <div class="locatpurposeBot clearfix">
                <div class="leaserrenewalBx1 leaseprior">
                    <span>Purchase Option Available Under the Contract
                        <strong>{{ ucfirst($asset->purchaseOption->purchase_option_clause) }}</strong>
                    </span>

                    <span>Expected Lease End Date
                        @if($asset->purchaseOption->purchase_option_clause == "yes" && $asset->purchaseOption->purchase_option_exerecisable == "yes")
                            <strong>{{ \Carbon\Carbon::parse($asset->purchaseOption->expected_lease_end_date)->format(config('settings.date_format')) }}</strong>
                        @else
                            <strong> - </strong>
                        @endif
                    </span>
                </div>

                <div class="leaserrenewalBx2 leaseprior">
                    <span>Reasonable Certainity to Exercise Purchase Option as of today
                        @if($asset->purchaseOption->purchase_option_clause == 'yes')
                            <strong>{{ ucfirst($asset->purchaseOption->purchase_option_exerecisable) }}</strong>
                        @else
                            <strong> - </strong>
                        @endif
                    </span>

                    <span>Currency
                        @if($asset->purchaseOption->purchase_option_clause == 'yes' && $asset->purchaseOption->purchase_option_exerecisable == 'yes')
                            <strong>{{ $asset->purchaseOption->currency }}</strong>
                        @else
                            <strong> - </strong>
                        @endif
                    </span>
                </div>

                <div class="leaserrenewalBx2 leaseprior">
                    <span>Expected Purchase Date
                        @if($asset->purchaseOption->purchase_option_clause == 'yes' && $asset->purchaseOption->purchase_option_exerecisable == 'yes')
                            <strong>{{ \Carbon\Carbon::parse($asset->purchaseOption->expected_purchase_date)->format(config('settings.date_format')) }}</strong>
                        @else
                            <strong> - </strong>
                        @endif
                    </span>
                    <span>Purchase Price
                        @if($asset->purchaseOption->purchase_option_clause == 'yes' && $asset->purchaseOption->purchase_option_exerecisable == 'yes')
                            <strong>{{ number_format($asset->purchaseOption->purchase_price, 2) }}</strong>
                        @else
                            <strong> - </strong>
                        @endif
                    </span>
                </div>

            </div>
        </div>
    @endif

<!--Lease Payments-->
    <div class="locatPurposeOutBx">
        <div class="locatpurposeTop leaseterminatHd">
            Lease Payments
        </div>
        <div class="leasepaymentTble">
            <table cellpadding="0" cellspacing="0" border="0" width="100%">
                <tr>
                    <th>Lease Payments</th>
                    <th>Type</th>
                    <th>Nature</th>
                    <th>LP Interval</th>
                    <th>Interval Point</th>
                    <th>First Lease Payment <br/>Start Date</th>
                    <th>Last Lease Payment End Date</th>
                    <th>Lease Currency</th>
                    <th>Amount Per Interval</th>
                    <th>Total UD Lease Payments</th>
                </tr>
                @foreach($asset->payments as $payment)
                    <tr>
                        <td>{{ $payment->name }}</td>
                        <td>{{ $payment->paymentType->title }}</td>
                        <td>{{ $payment->paymentNature->title }}</td>
                        <td>
                            @if($payment->paymentFrequency)
                                {{ $payment->paymentFrequency->title }}
                            @else
                                -
                            @endif

                        </td>
                        <td>
                            @if($payment->paymentInterval)
                                {{ $payment->paymentInterval->title }}
                            @else
                                -
                            @endif

                        </td>
                        <td>{{ \Carbon\Carbon::parse($payment->first_payment_start_date)->format(config('settings.date_format')) }}</td>
                        <td>{{ \Carbon\Carbon::parse($payment->last_payment_end_date)->format(config('settings.date_format')) }}</td>
                        <td>{{ $payment->payment_currency }}</td>
                        <td>{{ number_format($payment->payment_per_interval_per_unit) }}</td>
                        <td>{{ number_format($payment->getUndiscountedValue()) }}</td>
                    </tr>
                @endforeach
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
                    <th>Lease Payments</th>
                    <th>Lease Escalation Applied</th>
                    <th> Effective Date</th>
                    <th>Escalation <br> Basis</th>
                    <th>Escalation Consistently<br/>Applied</th>
                    <th>Fixed Rate</th>
                    <th>Variable Rate</th>
                    <th>Total Rate</th>
                    <th>Escalation Amount</th>
                    <th>Action</th>
                </tr>
                @foreach($asset->payments as $payment)
                    <tr>
                        <td>{{ $payment->name }}</td>
                        <td>
                            @if($payment->paymentEscalationSingle)
                                {{ ucfirst($payment->paymentEscalationSingle->is_escalation_applicable) }}
                            @else
                                No
                            @endif
                        </td>
                        <td>
                            @if($payment->paymentEscalationSingle && $payment->paymentEscalationSingle->is_escalation_applicable == "yes")
                                {{ \Carbon\Carbon::parse($payment->paymentEscalationSingle->effective_from)->format(config('settings.date_format')) }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($payment->paymentEscalationSingle && $payment->paymentEscalationSingle->is_escalation_applicable == "yes")
                                {{ $payment->paymentEscalationSingle->escalationBasis->title }}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($payment->paymentEscalationSingle && $payment->paymentEscalationSingle->is_escalation_applicable == "yes")
                                {{ ucfirst($payment->paymentEscalationSingle->is_escalation_applied_annually_consistently)}}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($payment->paymentEscalationSingle && $payment->paymentEscalationSingle->is_escalation_applicable == "yes")
                                {{ $payment->paymentEscalationSingle->fixed_rate}}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($payment->paymentEscalationSingle && $payment->paymentEscalationSingle->is_escalation_applicable == "yes")
                                {{ $payment->paymentEscalationSingle->current_variable_rate}}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($payment->paymentEscalationSingle && $payment->paymentEscalationSingle->is_escalation_applicable == "yes")
                                {{ $payment->paymentEscalationSingle->total_escalation_rate}}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($payment->paymentEscalationSingle && $payment->paymentEscalationSingle->is_escalation_applicable == "yes")
                                {{ $payment->paymentEscalationSingle->escalated_amount}}
                            @else
                                -
                            @endif
                        </td>
                        <td>
                            @if($lease->escalation_clause_applicable == "yes")
                                <a href="javascript:void(0);" class="btn btn-xs btn-primary show_escalation" data-payment_id="{{$payment->id}}" data-toggle="tooltip"
                                   title="View Escalation Chart"><i class="fa fa-eye"></i></a>
                            @else
                                &nbsp;
                            @endif
                        </td>
                    </tr>
                @endforeach
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
                    <strong>
                        @if($asset->residualGuranteeValue)
                            {{ ucfirst($asset->residualGuranteeValue->any_residual_value_gurantee) }}
                        @else
                            No
                        @endif
                    </strong>
                </span>

                <span>Nature of Guarantee
                    <strong>
                        @if($asset->residualGuranteeValue && $asset->residualGuranteeValue->nature)
                            {{ ucfirst($asset->residualGuranteeValue->nature->title) }}
                        @else
                            No
                        @endif
                    </strong>
                </span>

                <span>Currency
                    <strong>
                        @if($asset->residualGuranteeValue && $asset->residualGuranteeValue->any_residual_value_gurantee == 'yes')
                            {{ ucfirst($asset->residualGuranteeValue->currency) }}
                        @else
                            -
                        @endif
                    </strong>
                </span>
            </div>

            <div class="leaserrenewalBx2 leaseprior">
                <span>Variable Basis
                    <strong>
                        @if($asset->residualGuranteeValue && $asset->residualGuranteeValue->any_residual_value_gurantee == 'yes' && $asset->residualGuranteeValue->variable_basis_id)
                            {{ ucfirst($asset->residualGuranteeValue->variableBasis->title) }}
                        @else
                            -
                        @endif
                    </strong>
                </span>
                <span>Residual Value
                    <strong>
                        @if($asset->residualGuranteeValue && $asset->residualGuranteeValue->any_residual_value_gurantee == 'yes')
                            {{ number_format($asset->residualGuranteeValue->residual_gurantee_value, 2) }}
                        @else
                            -
                        @endif
                    </strong>
                </span>
            </div>

            <div class="leaserrenewalBx2 leaseprior">
                <span>Determinable
                    <strong>
                        @if($asset->residualGuranteeValue && $asset->residualGuranteeValue->any_residual_value_gurantee == 'yes')
                            {{ ucfirst($asset->residualGuranteeValue->amount_determinable) }}
                        @else
                            -
                        @endif
                    </strong>
                </span>
                <span>Total Residual Guarantee Value
                    <strong>
                        @if($asset->residualGuranteeValue && $asset->residualGuranteeValue->any_residual_value_gurantee == 'yes')
                            {{ number_format($asset->residualGuranteeValue->total_residual_gurantee_value,2) }}
                        @else
                            -
                        @endif
                    </strong>
                </span>

                <span>Description
                    <strong>
                        @if($asset->residualGuranteeValue && $asset->residualGuranteeValue->any_residual_value_gurantee == 'yes')
                            {{ $asset->residualGuranteeValue->other_desc }}
                        @else
                            -
                        @endif
                    </strong>
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
                    <strong>
                        @if($asset->leaseSelectDiscountRate)
                            {{ $asset->leaseSelectDiscountRate->interest_rate }}
                        @else
                            -
                        @endif
                    </strong>
                </div>
                <div class="discountBx">
                    <span>Annual Avg. Escalation Rate</span>
                    <strong>
                        @if($asset->leaseSelectDiscountRate)
                            {{ $asset->leaseSelectDiscountRate->annual_average_esclation_rate }}
                        @else
                            -
                        @endif
                    </strong>
                </div>
                <div class="discountBx">
                    <span>Discount Rate in Use</span>
                    <strong>
                        @if($asset->leaseSelectDiscountRate)
                            {{ $asset->leaseSelectDiscountRate->discount_rate_to_use }}
                        @else
                            -
                        @endif
                    </strong>
                </div>
                <div class="discountBx">
                    <span>Effective Daily Discount Rate</span>
                    <strong>
                        @if($asset->leaseSelectDiscountRate)
                            {{ $asset->leaseSelectDiscountRate->daily_discount_rate }}
                        @else
                            -
                        @endif
                    </strong>
                </div>
            </div>
            <div class="graphBx" id="chart">
                <canvas id="canvas"></canvas>
            </div>
        </div>
    </div>
    @if($asset->securityDeposit)
    <!--Lease Security Deposit-->
        <div class="locatPurposeOutBx">
            <div class="locatpurposeTop leaseterminatHd">
                Lease Security Deposit
            </div>
            <div class="locatpurposeBot clearfix">
                <div class="leaserrenewalBx1 leaseprior">
                <span>Any Security Deposit
                    <strong>{{ ucfirst($asset->securityDeposit->any_security_applicable)}}</strong>
                </span>
                    <span>Type of Secuity
                    <strong>Money Transfer</strong>
                </span>
                </div>

                <div class="leaserrenewalBx2 leaseprior">
                <span>Security Deposit Refundable <br/> or Adjustable ?
                    @if($asset->securityDeposit->refundable_or_adjustable == '1')
                        <strong>Refundable</strong>
                    @elseif($asset->securityDeposit->refundable_or_adjustable == '2')
                        <strong>Adjustable</strong>
                    @else
                        <strong> - </strong>
                    @endif
                </span>
                    <span>Currency
                    <strong>
                        {{ $asset->securityDeposit->currency}}
                    </strong>
                </span>
                </div>

                <div class="leaserrenewalBx2 leaseprior">
                <span>Date of Payment
                    <strong>
                        @if($asset->securityDeposit->any_security_applicable === 'yes')
                            {{ \Carbon\Carbon::parse($asset->securityDeposit->payment_date_of_security_deposit)->format(config('settings.date_format')) }}
                        @else
                            -
                        @endif
                    </strong>
                </span>
                    <span>Value
                    <strong>
                        @if($asset->securityDeposit->any_security_applicable === 'yes')
                            {{ number_format($asset->securityDeposit->total_amount, 2) }}
                        @else
                            -
                        @endif
                    </strong>
                </span>
                </div>
            </div>
        </div>
    @endif
</div>

