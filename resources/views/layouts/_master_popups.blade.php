<!-- for cookies popup modal -->
<div id="page_cookies_modal" class="modal fade" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><br></h4>
            </div>
            <div class="modal-body">
                <p>Website uses cookies to make your experience to use this website easy. Cookies cannot be used to
                    identify you personally. By visiting this website, you consent to WITSYNC’s use of cookies in
                    accordance with our Cookies Policy. To block, delete or manage cookies.</p>
                <p>Please visit www.aboutcookies.org, https://cookies.insites.com/ .</p>
            </div>
            <div class="modal-footer">
                <a href="javascript:void(0);" id="btn_disagree" class="btn btn1 btn_disagree">Disagree <i
                            class="fa fa-angle-right"></i></a>
                <a href="javascript:void(0);" id="btn_agree" class="btn btn2 btn_agree">Agree <i
                            class="fa fa-angle-right"></i></a>
                <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
            </div>
        </div>
    </div>
</div>


<!-- modal for subscription plan -->
<div id="pricing_Modal" class="modal fade pricing_Modal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">
        <form id="proceed_subscription_plan" action="{{ route('master.pricing.subscribe') }}">
            <!-- Modal content-->
            <div class="modal-content">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <div class="clearfix"></div>
                </div>
                <div class="modal-body">

                    <fieldset>
                        <h2>Thank You for Choosing Your Subscription Plan<br>You will be Charged Annually</h2>
                        <p>(Please note thePresent Value of Leases, Value of Lease Assets, Accrued Interest, Interest
                            Expense, Depreciation, Lease Cash Flows, Leasing Disclosures and Presentations are
                            calculated on
                            a daily basis and based on actual number of days involved.)</p>
                        <br>
                        <form name="myform" method="post">
                            <table class="table table-bordered">
                                <tbody>
                                <tr>
                                    <td>
                                        <small><b>Select Annual Subscription Plan</b></small>
                                    </td>
                                    <td>
                                        @php
                                            $first_discount = 0;
                                        @endphp
                                        <select class="form-control" name="selected_plan" id="splan">
                                            <option value="">Please select Plan</option>
                                            @foreach(getPaidSubscriptionPlans() as $plan)
                                                @php
                                                    if($first_discount == 0){
                                                        $first_discount = $plan->annual_discount;
                                                    }
                                                @endphp
                                                <option value="{{$plan->id}}" data-price="{{ $plan->price }}"
                                                        data-annaual_discount="{{ $plan->annual_discount }}">
                                                    $ {{ $plan->price }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <small>Select Subscription Years</small>
                                    </td>
                                    <td>
                                        <select class="form-control" id="syear" name="smonths">
                                            <option value="">Please select</option>
                                            <option value="12">1 Year</option>
                                            <option value="24">2 Year</option>
                                            <option value="36">3 Year</option>
                                        </select>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <small>Gross Value of Subscription</small>
                                    </td>
                                    <td class="text-right">$<span id="gvofs">0</span></td>
                                </tr>
                                <tr>
                                    <td>
                                        <small>Any Offer</small>
                                    </td>
                                    <td class="text-right"><span id="anyoffer">--</span></td>
                                </tr>
                                <tr>
                                    <td class="net-value-text">
                                        <small><b>Net Value of Subscription Payable.</b></small>
                                    </td>
                                    <td class="text-right net-value">$<span id="net_payable">0</span></td>
                                </tr>

                                </tbody>
                            </table>
                        </form>
                        <p>Apply your discount coupon, if any, at the time of payment</p>
                    </fieldset>

                    <fieldset>
                        <legend>Assured Benefit</legend>
                        <p>On every renewal, you will get <span
                                    id="annual_discount_for_selected">{{$first_discount}}</span>% additional discount on
                            the basic standard subscription plan
                            price.</p>
                    </fieldset>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn proceed">Proceed</button>
                </div>

            </div>
        </form>
    </div>
</div>

@include('layouts._build_your_plan_popup')

<!-- for leasing software page popup -->
<div id="leasing_software" class="modal fade pricing_Modal" role="dialog" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog modal-lg">

        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="clearfix"></div>
            </div>
            <div class="modal-body">
                <div style="border: 2px solid #eaeaea;padding: 20px;margin-bottom: 22px;font-size: 16px;">
                    <p><i class="fa fa-quote-left"></i> Please choose your subscription plan to complete your
                        registration for Lessee Leasing Management Software.<i class="fa fa-quote-right"></i></p>
                    <div class="text-right"><br><br>
                        <a href="{{ route('master.pricing.index') }}" class="btn proceed">Proceed</a>
                    </div>
                    <br>
                </div>
            </div>
        </div>

    </div>
</div>

