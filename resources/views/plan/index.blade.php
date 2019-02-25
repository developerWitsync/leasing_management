@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <!-- END CSS for this page -->
@endsection
@section('content')
    <div class="panel panel-default">

        <div class="panel-body">

            @if (session('status'))
                <div class="alert alert-success">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            <div class="tab-content" style="padding: 0px;">
                <div role="tabpanel" class="tab-pane active">
                    <div class="row">
                        <div class="col-md-12 col-sm-12">
                            @if($subscription)
                                <h4>Your Subscription : {{ $subscription->subscriptionPackage->title }}</h4>
                                <table class="table subscription_table">
                                    <tbody style="border: 1px solid #d1d1d1; background: #f9f9f9;">
                                    <tr>
                                        <th>Subscription:</th>
                                        <td>{{ $subscription->subscriptionPackage->title }}</td>
                                    </tr>
                                    <tr>
                                        <th>Billing period:</th>
                                        <td>{{ \Carbon\Carbon::parse($subscription->created_at)->format(config('settings.date_format')) }} to {{ \Carbon\Carbon::parse($subscription->subscription_expire_at)->format(config('settings.date_format')) }}</td>
                                    </tr>
                                    <tr>
                                        <th>Allowed Lease Assets:</th>
                                        <td>{{ $subscription->subscriptionPackage->available_leases }}</td>
                                    </tr>
                                    <tr>
                                        <th>Remaining Lease Assets:</th>
                                        <td>{{ $subscription->subscriptionPackage->available_leases - $already_created_leases }}</td>
                                    </tr>

                                    </tbody>
                                </table>
                            @else
                                <h4>Your Subscription : No Plan Purchased</h4>
                            @endif
                        </div>
                    </div>

                    <div class="row upgradeSubscriptionPlans" style="padding-left: 14px;padding-right: 12px;">
                        @php
                            $classes_array = ['panel-primary', 'panel-info', 'panel-success', 'panel-primary', 'panel-info'];
                            $current_plan_key = -1;
                        @endphp
                        @foreach($plans as $key=>$plan)
                            <div class="col-md-5th-1 col-sm-4 col-md-offset-0 col-sm-offset-2">
                            <div class="panel  {{ $classes_array[$key] }}">

                                @if($plan->most_popular)
                                    <div class="cnrflash">
                                        <div class="cnrflash-inner">
                                            <span class="cnrflash-label">MOST<br>
                                            POPULR</span>
                                        </div>
                                    </div>
                                @endif

                                <div class="panel-heading">
                                    <h3 class="panel-title">{{ $plan->title }}</h3>
                                </div>
                                <div class="panel-body">
                                    <div class="the-price">
                                        @if($plan->price_plan_type == 1)
                                            @if(is_null($plan->price))
                                                Free
                                            @else
                                                <h3>$ {{(int)$plan->price}}<span class="subscript">/Month*</span></h3>
                                            @endif
                                        @else
                                            Will be Assessed
                                        @endif

                                    </div>
                                    <table class="table">
                                        <tr>
                                            <td>
                                                @if($plan->price_plan_type == 1 )
                                                    @if(is_null($plan->available_leases))
                                                        Unlimited
                                                    @else
                                                        Upto {{ $plan->available_leases }} Lease Asset
                                                    @endif
                                                @else
                                                    You Choose
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="@if($subscription && $subscription->plan_id == $plan->id) active @endif">
                                            <td>
                                                @if($plan->price_plan_type == 1 )
                                                    @if(is_null($plan->available_users))
                                                        Unlimited
                                                    @else
                                                        {{ $plan->available_users }} Sub-Users
                                                    @endif
                                                @else
                                                    You Choose
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                @if($plan->hosting_type == 'cloud')
                                                    Cloud Hosting
                                                @elseif($plan->hosting_type == 'on-premise')
                                                    On-Premises Hosting
                                                @else
                                                    Cloud/On-Premise Hosting
                                                @endif
                                            </td>
                                        </tr>
                                        <tr class="@if($subscription && $subscription->plan_id == $plan->id) active @endif">
                                            <td>
                                                @if(is_null($plan->validity))
                                                    Unlimited**
                                                @else
                                                    {{ $plan->validity }} Days
                                                @endif
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                User Guide
                                            </td>
                                        </tr>
                                        <tr class="@if($subscription && $subscription->plan_id == $plan->id) active @endif">
                                            <td>
                                                ----
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="panel-footer">
                                    <a href="{{ route('plan.purchase', ['plan' => $plan->slug]) }}" class="btn btn-sm btn-success" role="button">
                                        @if($subscription && ($subscription->plan_id == $plan->id))
                                            Expiring on {{ \Carbon\Carbon::parse($subscription->subscription_expire_at)->format('Y-m-d') }}
                                        @elseif($subscription && $current_plan_key > -1 && $current_plan_key < $key)
                                            Upgrade
                                        @elseif($subscription)
                                            DownGrade
                                        @else
                                            Purchase
                                        @endif
                                    </a>
                                </div>
                            </div>
                        </div>
                            @php
                                if($subscription && ($subscription->plan_id == $plan->id)){
                                    $current_plan_key = $key;
                                }
                            @endphp
                        @endforeach
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
@section('footer-script')

@endsection
