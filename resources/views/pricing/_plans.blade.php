<section style="padding-top: 0;"><br>
    <div class="pricing_main">
        <div class="col-md-8 col-md-offset-2 center">
            <h2>Choose your subscription plan that meets your business needs</h2>
        </div>
        <div class="container">
            <div class="row">
                @php
                    $t = 1;
                @endphp
                @foreach($subscription_plans as $plan)
                    <div class="col-md-5th-1 col-sm-4 col-md-offset-0 col-sm-offset-2">
                        <div class="pricingTable11 @if($t > 1) t{{$t}} @endif">
                            <div class="pricingTable-header">
                                <!-- <i class="fa fa-adjust"></i> -->
                                <div class="price-value">
                                    <div class="heading1"> {{$plan->title}}</div>

                                    @if($plan->price_plan_type == 1)
                                        @if(is_null($plan->price))
                                            <span class="rs">
                                                $ {{(int)$plan->price}}
                                            </span>
                                        @else
                                            <span class="rs">
                                                $ {{(int)$plan->price}}
                                            </span>
                                        @endif
                                    @else
                                        <span class="rs last">
                                            Will be Assessed
                                        </span>
                                    @endif
                                    <span class="month">
                                         @if($plan->price_plan_type == 1)
                                            @if(is_null($plan->price))
                                                Free
                                            @else
                                                Per Month*
                                            @endif
                                        @else
                                            &nbsp;
                                        @endif
                                    </span></div>
                            </div>
                            <h3 class="heading"></h3>
                            <!-- <h3 class="heading">Standard</h3> -->
                            <div class="pricing-content">
                                <ul>
                                    <li>Manage Up to <br><b>
                                            @if($plan->price_plan_type == 1 )
                                                @if(is_null($plan->available_leases))
                                                    Unlimited
                                                @else
                                                    {{ $plan->available_leases }} Lease Asset
                                                @endif
                                            @else
                                                You Choose
                                            @endif
                                        </b></li>
                                    <li>
                                        <b>
                                            @if($plan->price_plan_type == 1 )
                                                @if(is_null($plan->available_users))
                                                    Unlimited
                                                @else
                                                    {{ $plan->available_users }} Users
                                                @endif
                                            @else
                                                You Choose
                                            @endif
                                        </b>
                                    </li>
                                    <li>
                                        <b>
                                            @if($plan->hosting_type == 'cloud')
                                                Cloud Hosting
                                            @elseif($plan->hosting_type == 'on-premise')
                                                On-Premises Hosting
                                            @else
                                                Cloud/On-Premise Hosting
                                            @endif
                                        </b>
                                    </li>

                                    <li>Validity
                                        <b>
                                            @if(is_null($plan->validity))
                                                Unlimited**
                                            @else
                                                {{ $plan->validity }} Days
                                            @endif
                                        </b>
                                    </li>

                                    <li>Support
                                        <b>User Guide</b>
                                    </li>

                                    <li>Features
                                        <b>Limited</b>
                                    </li>

                                    <li>2 Year Subscription Offer <br>
                                        <b>
                                            @if($plan->annual_discount > 0)
                                                {{ (int)$plan->annual_discount }}% Off
                                            @else
                                                ----
                                            @endif
                                        </b>
                                    </li>

                                    <li>3 Year Subscription Offer <br>
                                        <b>
                                            @if($plan->annual_discount > 0)
                                                {{ (int)$plan->annual_discount * 2 }}% Off
                                            @else
                                                ----
                                            @endif
                                        </b>
                                    </li>

                                </ul>
                            </div>
                            <div class="pricingTable-signup">
                                @if($plan->price_plan_type == 1 )
                                    @if(is_null($plan->price))
                                        <a href="{{ route('register.index', ['package'=>$plan->slug]) }}">Subscribe Now</a>
                                    @else
                                        <a href="#" data-toggle="modal" data-target="#pricing_Modal">Subscribe Now</a>
                                    @endif
                                @else
                                    <a href="#" data-toggle="modal" data-target="#buildYourPlan_Modal">Contact Now</a>
                                @endif
                            </div>
                        </div>
                    </div>
                    @php
                        $t = $t +1;
                    @endphp
                @endforeach
            </div>
            <div class="row">
                <div class="col-md-12 text-center">
                    <br>
                    <br>
                    <div>
                        <small>* On minimum subscription of 1 year</small>
                    </div>
                    <div>
                        <small>** Unlimited till end of subscription period</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<section class="Build_plan">
    <div class="container">
        <div class="row">
            <div class="col-md-7">
                <h3>Unsure Which Plan is Right For You?</h3>
                <p>Tell us what you need and we’ll develop a plan that’s right for your business and provide support
                    whenever you needed.</p>
            </div>
            <div class="col-md-4 pull-right text-center">
                <a href="javascript:void(0);" class="btn btn-primary" data-toggle="modal"
                   data-target="#buildYourPlan_Modal">BUILD YOUR PLAN</a>
                <!-- <a href="mailto:test@test.com" class="btn btn-primary">BUILD YOUR PLAN</a> -->
            </div>
        </div>
    </div>
</section>
