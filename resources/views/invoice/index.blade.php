@extends('layouts.invoice')
@section('content')
    <table cellspacing="0" cellpadding="0" border="0" width="100%" style="font-size: 15px; color: #000;">
        <tr>
            <td width="30%"
                style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                Business Account ID
            </td>
            <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{$user->account_id}}</td>
        </tr>
        <tr>
            <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                Company Name
            </td>
            <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ $user->legal_entity_name }}</td>
        </tr>
        {{--<tr>--}}
        {{--<td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">--}}
        {{--Registered Address (Place of Supply)--}}
        {{--</td>--}}
        {{--<td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{  }}</td>--}}
        {{--</tr>--}}
        <tr>
            <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                Country
            </td>
            <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ $user->country }}</td>
        </tr>
        @if(strtolower($user->country) == "india")
            <tr>
                <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                    State
                </td>
                <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ $user->state}}
                </td>
            </tr>
            <tr>
                <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                    GST
                </td>
                <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ $user->gstin }}
                </td>
            </tr>
        @endif
        <tr>
            <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                Invoice Sequence Number
            </td>
            <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                <span style="font-size: 13px; color: #666;">{{ $invoice_number }}</span>
            </td>
        </tr>
        <tr>
            <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                Software ID
            </td>
            <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ env('SOFTWARE_ID') }}</td>
        </tr>
        <tr>
            <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                Subscription Validity
            </td>
            <td>
                <table cellspacing="0" cellpadding="0" border="0" width="100%">
                    <tr>
                        <td width="40%"
                            style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                            Date
                            From: {{ \Carbon\Carbon::parse($subscription->created_at)->format(config('settings.date_format')) }}
                        </td>
                        <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;"><span
                                    style="width: 150px; display: inline-block;">Date to:</span>
                            <span>{{ \Carbon\Carbon::parse($subscription->subscription_expire_at)->format(config('settings.date_format')) }} (12:00 Mid Night)</span>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                Date of Payment
            </td>
            <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ \Carbon\Carbon::parse($subscription->created_at)->format('F j,Y H:i:s') }}
            </td>
        </tr>
        @if($subscription->geteway_transasction_id)
            <tr>
                <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                    Transaction ID
                </td>
                <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ $subscription->geteway_transasction_id }}
                </td>
            </tr>
        @endif
        <tr>
            <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                Payment Mode
            </td>
            <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ $subscription->payment_method }}
            </td>
        </tr>
        <tr>
            <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                Payment Channel
            </td>
            <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">Pay Pal</td>
        </tr>
    </table>
    <table cellspacing="0" cellpadding="0" border="0" width="100%">
        <tr>
            <th width="70%"
                style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; font-weight:normal; font-size: 15px; text-align: left; background: #f2f2f2; padding:7px 10px; line-height: 25px;">
                Payment Particulars
            </th>
            <th style=" border-bottom: #808080 solid 1px;  background: #f2f2f2;  font-size: 15px; font-weight:normal;  text-align: left; padding:7px 10px; line-height: 25px;">
                Amount (US Dollar)
            </th>
        </tr>
        <tr>
            <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                {{ $subscription->subscriptionPackage->title }}
            </td>
            <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ $subscription->subscriptionPackage->price }}</td>
        </tr>
        <tr>
            <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                Purchased For Months
            </td>
            <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ $subscription->subscription_years * 12 }}</td>
        </tr>
        @if($subscription->subscriptionPackage->annual_discount)
            <tr>
                <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                    Discount at {{ (int)$subscription->subscriptionPackage->annual_discount }}%
                </td>
                <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ $subscription->discounted_amount }}</td>
            </tr>
        @endif

        @if($subscription->coupon_code && $subscription->coupon_discount > 0)
            <tr>
                <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                    Coupon Code {{ $subscription->coupon_code }} discount
                </td>
                <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ $subscription->coupon_discount }}</td>
            </tr>
        @endif

        <tr>
            <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                Adjusted Amount
            </td>
            <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ $subscription->adjusted_amount }}</td>
        </tr>
        <tr>
            <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                Used Credits
            </td>
            <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ $subscription->credits_used }}</td>
        </tr>
        <tr>
            <td style=" border-bottom: #808080 solid 1px; border-right: #808080 solid 1px; padding:7px 10px; line-height: 25px;">
                Net Payable
            </td>
            <td style=" border-bottom: #808080 solid 1px; padding:7px 10px; line-height: 25px;">{{ $subscription->paid_amount - $subscription->discounted_amount }}</td>
        </tr>
    </table>
@endsection