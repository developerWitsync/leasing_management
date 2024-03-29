<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Subscription Plan Payments Details</h4>
</div>
<div class="modal-body">
    <table class="table table-condensed table-bordered">
        @if(isset($credit_or_balance['old_plan_period']))
            <tr>
                <td>Old Subscription Plan Period</td>
                <td>{{ $credit_or_balance['old_plan_period'] }}</td>
            </tr>
        @endif

        @if(isset($credit_or_balance['old_plan_price']))
            <tr>
                <td>Old Plan Subscription Price</td>
                <td>{{ $credit_or_balance['old_plan_price'] }}</td>
            </tr>
        @endif

        @if(isset($credit_or_balance['total_subscription_days']))
            <tr>
                <td>Total Subscription Days</td>
                <td>{{ $credit_or_balance['total_subscription_days'] }}</td>
            </tr>
        @endif

        @if(isset($credit_or_balance['old_plan_per_day_rate']))
            <tr>
                <td>Old Plan Per Day Rate</td>
                <td>{{ $credit_or_balance['old_plan_per_day_rate'] }}</td>
            </tr>
        @endif

        @if(isset($credit_or_balance['old_plan_effective_untill']))
            <tr>
                <td>Old Plan Effective Until</td>
                <td>{{ $credit_or_balance['old_plan_effective_untill'] }}</td>
            </tr>
        @endif

        @if(isset($credit_or_balance['old_plan_subscription_days']))
            <tr>
                <td>Old Plan Subscription Days</td>
                <td>{{ $credit_or_balance['old_plan_subscription_days'] }}</td>
            </tr>
        @endif

        @if(isset($credit_or_balance['old_plan_price_levied']))
            <tr>
                <td>Old Plan Price Levied</td>
                <td>{{ $credit_or_balance['old_plan_price_levied'] }}</td>
            </tr>
        @endif

        @if(isset($credit_or_balance['new_subscription_period']))
            <tr>
                <td>New Subscription Period</td>
                <td>{{ $credit_or_balance['new_subscription_period'] }}</td>
            </tr>
        @endif

        @if(isset($credit_or_balance['new_plan_price']))
            <tr>
                <td>New Plan Price</td>
                <td>{{ $credit_or_balance['new_plan_price'] }}</td>
            </tr>
        @endif

        @if(isset($credit_or_balance['new_plan_total_subscription_days']))
            <tr>
                <td>New Plan Total Subscription Days</td>
                <td>{{ $credit_or_balance['new_plan_total_subscription_days'] }}</td>
            </tr>
        @endif
        @if(isset($credit_or_balance['new_plan_per_day_rate']))
            <tr>
                <td>New Plan Per Day Rate</td>
                <td>{{ $credit_or_balance['new_plan_per_day_rate'] }}</td>
            </tr>
        @endif

        @if(isset($credit_or_balance['effective_date_of_new_plan']))
            <tr>
                <td>Effective Date of New Plan</td>
                <td>{{ $credit_or_balance['effective_date_of_new_plan'] }}</td>
            </tr>
        @endif
        @if(isset($credit_or_balance['new_plan_subscroption_days']))
            <tr>
                <td>New Plan Subscription Days</td>
                <td>{{ $credit_or_balance['new_plan_subscroption_days'] }}</td>
            </tr>
        @endif

        @if(isset($credit_or_balance['new_plan_price_levied']))
            <tr>
                <td>New Plan Price Levied</td>
                <td>{{ $credit_or_balance['new_plan_price_levied'] }}</td>
            </tr>
        @endif

        @if(isset($credit_or_balance['total_payable']))
            <tr>
                <td>Total Payable</td>
                <td>{{ $credit_or_balance['total_payable'] }}</td>
            </tr>
        @endif

        @if(isset($credit_or_balance['price_paid']))
            <tr>
                <td>Price Paid</td>
                <td>{{ $credit_or_balance['price_paid'] }}</td>
            </tr>
        @endif


        @if(isset($credit_or_balance['final_payment_amount']))
            @if($credit_or_balance['final_payment_amount'] > 0)
                <tr>
                    <td>Balance that will be credited to your witsync credit balance</td>
                    <td>${{ $credit_or_balance['final_payment_amount'] }}</td>
                </tr>
            @else
                <tr>
                    <td>Total Amount that you need to Pay</td>
                    <td>${{ -1 * $credit_or_balance['final_payment_amount'] }}</td>
                </tr>
            @endif
        @endif

    </table>
</div>
<div class="modal-footer">
    <a class="btn btn-default" href="{{ route('plan.purchase', ['plan' => $plan]) }}">Submit</a>
</div>