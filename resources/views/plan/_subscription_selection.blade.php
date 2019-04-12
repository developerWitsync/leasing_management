<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal">&times;</button>
    <h4 class="modal-title">Subscription Plan Payments Details</h4>
</div>
<div class="modal-body">
    <fieldset>
        <h2>Thank You for Choosing Your Subscription Plan<br>You will be Charged Annually</h2>
        <p>(Please note that Present Value of Leases, Value of Lease Assets, Accrued Interest, Interest Expense,
            Depreciation, Lease Cash Flows, Leasing Disclosures and Presentations are calculated on a daily basis and
            based on actual number of days involved.)</p>
        <br>
        <form name="myform" method="post">
            <table class="table table-bordered">
                <tbody>
                <tr>
                    <td>
                        <small><b>Selected Subscription Plan</b></small>
                    </td>
                    <td>
                        {{ $selected_package->title }}
                        <input type="hidden" name="selected_plan" value="{{ $selected_package->id }}"
                               id="selected_plan">
                    </td>
                </tr>
                <tr>
                    <td>
                        <small>Select Subscription Years</small>
                    </td>
                    <td>
                        <select class="form-control" id="months" name="months">
                            <option value="" disabled="disabled" selected="selected">Please select</option>
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

                <input type="hidden" name="action" value="{{$action}}" />

                @if($action != 'downgrade')
                    <tr class="coupon_code_discount_row" style="display: none">
                        <td>
                            <small>Coupon Discount</small>
                        </td>
                        <td class="text-right"><span id="coupon_discount">---</span></td>
                    </tr>
                @endif

                <tr>
                    <td>
                        <small>Adjusted Amount as per current subscription</small>
                    </td>
                    <td class="text-right"><span id="adjusted_amount">--</span></td>
                </tr>
                <tr>
                    <td>
                        <small id="credit_or_balance_label"><b>Credit/Balance Amout</b></small>
                    </td>
                    <td class="text-right"><span id="credit_or_balance">$0</span></td>
                </tr>

                </tbody>
            </table>
        </form>


        {{--<p>Apply your discount coupon, if any, at the time of payment</p>--}}
    </fieldset>
    @if($action != 'downgrade')
        <fieldset>
            <legend>Apply Coupon</legend>
            <p>
                <small>Enter coupon code below in case you have any coupon code.</small>
            </p>
            <div class="row">
                <div class="col-md-8">
                    <input class="form-control" type="text" name="coupon_code" placeholder="Enter Coupon Code">
                </div>
                <div class="col-md-4">
                    <a href="javascript:void(0);" class="btn btn-info apply_coupon_code">Apply Coupon</a>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Assured Benefit</legend>
            <p>On every renewal, you will get 10% additional discount on the basic standard subscription plan price.</p>
        </fieldset>
    @endif
</div>
<div class="modal-footer">
    <a href="javascript:void(0);" class="btn btn-info proceed upgrade_proceed">Proceed</a>
</div>
