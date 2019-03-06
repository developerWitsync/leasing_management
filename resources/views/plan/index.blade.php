@extends('layouts.app')
@section('header-styles')
    <link rel="stylesheet" href="{{ asset('css/jquery-ui.css') }}">
    <!-- BEGIN CSS for this page -->
    <link rel="stylesheet" type="text/css"
          href="{{ asset('assets/plugins/datatables/dataTables.bootstrap4.min.css') }}"/>
    <!-- END CSS for this page -->
    <style>
        .error{
            color: red;font-style: italic;
        }
    </style>
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
                            @php
                                if($custom_plan && $plan->price_plan_type == '2'){
                                    continue;
                                }
                            @endphp
                            <div class="col-md-5th-1 col-sm-4 col-md-offset-0 col-sm-offset-2">
                            <div class="panel  {{ $classes_array[$key] }}">

                                @if($plan->most_popular)
                                    <div class="cnrflash">
                                        <div class="cnrflash-inner">
                                            <span class="cnrflash-label">MOST<br>
                                            POPULAR</span>
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
                                                @if($plan->annual_discount > 0)
                                                    {{ (int)$plan->annual_discount }}% Off on Annual Subscription
                                                @else
                                                    ----
                                                @endif
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                                <div class="panel-footer">
                                    @if($plan->price_plan_type == 1)
                                        @if($subscription && ($subscription->plan_id == $plan->id))
                                            <span class="badge badge-info">
                                                Expiring on {{ \Carbon\Carbon::parse($subscription->subscription_expire_at)->format('Y-m-d') }}
                                            </span>
                                        @elseif($subscription && $current_plan_key > -1 && $current_plan_key < $key)
                                            <a href="{{ route('plan.purchase.subscriptionselection', ['plan' => $plan->slug]) }}" class="btn btn-sm btn-success purchase-plan" role="button">
                                                Upgrade
                                            </a>
                                        @elseif($subscription)
                                            <a href="{{ route('plan.purchase.subscriptionselection', ['plan' => $plan->slug]) }}" class="btn btn-sm btn-success purchase-plan" role="button">
                                                DownGrade
                                            </a>
                                        @else
                                            <a href="{{ route('plan.purchase.subscriptionselection', ['plan' => $plan->slug]) }}" class="btn btn-sm btn-success purchase-plan" role="button">
                                                Purchase
                                            </a>
                                        @endif
                                    @else
                                        <a href="#" data-toggle="modal" class="btn btn-sm btn-success build_your_own_plan" data-target="#buildYourPlan_Modal">Contact Now</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                            @php
                                if($subscription && ($subscription->plan_id == $plan->id)){
                                    $current_plan_key = $key;
                                }
                            @endphp
                        @endforeach

                        @if($custom_plan)
                            <div class="col-md-5th-1 col-sm-4 col-md-offset-0 col-sm-offset-2">
                                <div class="panel panel-success">

                                    @if($custom_plan->most_popular)
                                        <div class="cnrflash">
                                            <div class="cnrflash-inner">
                                            <span class="cnrflash-label">MOST<br>
                                            POPULAR</span>
                                            </div>
                                        </div>
                                    @endif

                                    <div class="panel-heading">
                                        <h3 class="panel-title">{{ $custom_plan->title }}</h3>
                                    </div>
                                    <div class="panel-body">
                                        <div class="the-price">
                                            <h3>$ {{(int)$custom_plan->price}}<span class="subscript">/Month*</span></h3>
                                        </div>
                                        <table class="table">
                                            <tr>
                                                <td>
                                                    Upto {{ $custom_plan->available_leases }} Lease Asset
                                                </td>
                                            </tr>
                                            <tr class="@if($subscription && $subscription->plan_id == $custom_plan->id) active @endif">
                                                <td>
                                                    {{ $custom_plan->available_users }} Sub-Users
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    @if($custom_plan->hosting_type == 'cloud')
                                                        Cloud Hosting
                                                    @elseif($custom_plan->hosting_type == 'on-premise')
                                                        On-Premises Hosting
                                                    @else
                                                        Cloud/On-Premise Hosting
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr class="@if($subscription && $subscription->plan_id == $custom_plan->id) active @endif">
                                                <td>
                                                    @if(is_null($custom_plan->validity))
                                                        Unlimited**
                                                    @else
                                                        {{ $custom_plan->validity }} Days
                                                    @endif
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    User Guide
                                                </td>
                                            </tr>
                                            <tr class="@if($subscription && $subscription->plan_id == $custom_plan->id) active @endif">
                                                <td>
                                                    @if($custom_plan->annual_discount > 0)
                                                        {{ (int)$custom_plan->annual_discount }}% Off on Annual Subscription
                                                    @else
                                                        ----
                                                    @endif
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="panel-footer">
                                            @if($subscription && ($subscription->plan_id == $custom_plan->id))
                                                <span class="badge badge-info">
                                                Expiring on {{ \Carbon\Carbon::parse($subscription->subscription_expire_at)->format('Y-m-d') }}
                                            </span>
                                            @else
                                                <a href="{{ route('plan.purchase.subscriptionselection', ['plan' => $custom_plan->slug]) }}" class="btn btn-sm btn-success purchase-plan" role="button">
                                                    Purchase
                                                </a>
                                            @endif
                                    </div>
                                </div>
                            </div>
                        @endif

                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->

    <div id="pricing_Modal" class="modal fade pricing_Modal" role="dialog" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content" id="up_down_plan">

            </div>

        </div>
    </div>
    <!-- Modal -->

    @include('layouts._build_your_plan_popup')


@endsection
@section('footer-script')
    <script>
        $(function(){
            $('.purchase-plan').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    url : $(this).attr('href'),
                    dataType : 'json',
                    success : function(response){
                        if(response['status']){
                            $('#up_down_plan').html(response['view']);
                            $('#pricing_Modal').modal('show');
                        } else {
                            alert(response['message']);
                        }
                    }
                })
            });
        });


        $(function(){
            $(document.body).on('change', '#months', function(){
                $.ajax({
                    url : '{{ route("plan.purchase.showadjustments") }}',
                    data : {
                        months : $(this).val(),
                        plan : $('#selected_plan').val()
                    },
                    dataType : 'json',
                    beforeSend: function(){
                      $("span.error").html('').hide();
                    },
                    success : function(response){
                        if(response['status']){
                            $('#gvofs').html(response['gross_value_of_new_plan']);
                            $('#anyoffer').html(response['discounted_percentage']+"%");
                            $('#adjusted_amount').html("$ "+response['adjusted_amount']);
                            if(response['balance'] > 0){
                                //this means that we have to credit some amount to users account...
                                $('#credit_or_balance_label').html('<b>Amount that will be credited to your witsync account</b>');
                                $("#credit_or_balance").html("$ "+response['balance']);
                            } else {
                                $('#credit_or_balance_label').html('<b>Net Payable</b>');
                                var payable = parseFloat(response['balance']) * -1;
                                $("#credit_or_balance").html("$ "+payable);
                            }

                            $('#pricing_Modal').modal('show');
                        } else {
                            alert(response['message']);
                        }
                    }
                });
            });

            $(document.body).on('click', '.upgrade_proceed', function(){
                $.ajax({
                    url : '{{ route("plan.purchase") }}',
                    type : 'post',
                    data : {
                        months : $('#months').val(),
                        plan : $('#selected_plan').val()
                    },
                    dataType : 'json',
                    beforeSend: function(){
                        $("span.error").html('').hide();
                    },
                    success : function(response){
                        if(response['status']){
                            // alert(response['redirect_link']);
                             window.location.href = response['redirect_link'];
                        } else {
                            if(typeof (response.errors)!="undefined"){
                                var errors = response.errors;
                                $.each(errors, function (i, e) {
                                    $('select[name="' + i + '"], input[name="' + i + '"]').after('<span class="error">' + e.join('1') + '</span>');
                                });
                            } else if(typeof(response.errorMessage)!="undefined"){
                                alert(response.errorMessage);
                            }
                        }
                    }
                });
            });
        });

    </script>
@endsection
