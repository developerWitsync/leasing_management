<section class="s_plans">
    <div class="container">
        <div class="row">
            <div class="col-md-8 center wow fadeInDown  col-md-offset-2">
                <h2>Choose your subscription plan that meets your business needs</h2>
                <!-- <h2>Subscription Plan</h2>   -->
            </div>
        </div>

        <div class="row">
            <div class="pricing-area text-center">
                @foreach($subscription_plans as $plan)
                    <div class="s_plan_main2 col-md-5th-1 col-sm-4 col-md-offset-0 col-sm-offset-2 @if($plan->most_popular) high-light-div @endif">
                        <div class="plan price-one wow fadeInDown">
                            <ul>
                                <li class="heading-one"><h1>{{$plan->title}}</h1>
                                    @if($plan->price_plan_type == '1' && is_null($plan->price))
                                        <div class="corner-ribbon top-right sticky blue">Free Trial Plan</div>
                                    @endif

                                    @if($plan->most_popular)
                                        <div class="corner-ribbon top-right sticky blue">Most Popular</div>
                                    @endif
                                </li>
                                <li class="s_head">Fee <h2>
                                        @if($plan->price_plan_type == 1)
                                            @if(is_null($plan->price))
                                                Free
                                            @else
                                                $ {{(int)$plan->price}}/Month*
                                            @endif
                                        @else
                                            Will be Assessed
                                        @endif
                                    </h2>
                                </li>
                                <li class="s_head">Manage Lease Assets<h2>
                                        @if($plan->price_plan_type == 1 )
                                            @if(is_null($plan->available_leases))
                                                Unlimited
                                            @else
                                                Upto {{ $plan->available_leases }} Lease Asset
                                            @endif
                                        @else
                                            You Choose
                                        @endif
                                    </h2>
                                </li>
                                <li class="s_head">Users
                                    <h2>

                                        @if($plan->price_plan_type == 1 )
                                            @if(is_null($plan->available_users))
                                                Unlimited
                                            @else
                                                {{ $plan->available_users }} Sub-Users
                                            @endif
                                        @else
                                            You Choose
                                        @endif
                                    </h2>
                                </li>
                                <li class="s_head">Hosting
                                    <h2>
                                        @if($plan->hosting_type == 'cloud')
                                            Cloud Hosting
                                        @elseif($plan->hosting_type == 'on-premise')
                                            On-Premises Hosting
                                        @else
                                            Cloud/On-Premise Hosting
                                        @endif
                                    </h2>
                                </li>
                                <li class="s_head">Validity
                                    <h2>
                                        @if(is_null($plan->validity))
                                            Unlimited**
                                        @else
                                            {{ $plan->validity }} Days
                                        @endif
                                    </h2>
                                </li>
                                <li class="s_head">Support<h2>User Guide</h2></li>
                                <li class="s_head">Offer<h2>
                                        @if($plan->annual_discount > 0)
                                            {{ (int)$plan->annual_discount }}% Off on Annual Subscription
                                        @else
                                            ----
                                        @endif
                                    </h2></li>
                                <li class="plan-action">
                                    <a href="{{ route('register.index', ['package'=>$plan->slug]) }}" class="btn btn-primary">Subscribe Now</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        <div class="row">
            <div class="col-md-12 text-center">
                <div><small>*   On minimum subscription of 1 year</small></div>
                <div><small>** Unlimited till end of subscription period</small></div>
            </div>
        </div>
    </div><!--/container-->
</section><!--/pricing-page-->