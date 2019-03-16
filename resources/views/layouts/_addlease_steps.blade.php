<div class="itemTab">
    <ul class="ul_carousel owl-carousel ">
        <li @if(1 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'lessor-details') active @endif" href="#">
                <i>1</i>
                <span>Lessor <br> Detail</span>
            </a>
        </li>
        <li @if(2 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'underlying-lease-assets') active @endif"
               href="{{ route('addlease.leaseasset.index', ['id'=>$lease->id])}}">
                <i>2</i>

                <span>Underlying Lease Asset</span>
            </a>
        </li>
        <li @if(3 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'lease-termination-option') active @endif"
               href="{{route('addlease.leaseterminationoption.index',['id'=>$lease->id])}}">
                <i>3</i>
                <span>Termination Option</span>
            </a>
        </li>
        <li @if(4 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'lease-renewal-option') active @endif"
               href="{{route('addlease.renewable.index',['id'=>$lease->id])}}">
                <i>4</i>
                <span>Renewal Option</span>
            </a>
        </li>
        <li @if(5 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'purchase-option') active @endif"
               href="{{route('addlease.purchaseoption.index',['id'=>$lease->id])}}">
                <i>5</i>
                <span>Purchase Option</span>
            </a>
        </li>
        <li @if(6 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'payments') active @endif"
               href="{{route('addlease.payments.index',['id'=>$lease->id])}}">
                <i>6</i>
                <span>Add Lease Payments</span>
            </a>
        </li>

        <li @if(7 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'residual-value-gurantee') active @endif"
               href="{{route('addlease.residual.index',['id'=>$lease->id])}}">
                <i>7</i>
                <span>Residual Value Guarantee</span>
            </a>
        </li>

        <li @if(8 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'lease-duration-classified') active @endif"
               href="{{route('addlease.durationclassified.index',['id'=>$lease->id])}}">
                <i>8</i>
                <span>Lease Duration Classified</span>
            </a>
        </li>

        <li @if(9 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'escalation') active @endif"
               href="{{route('lease.escalation.index',['id'=>$lease->id])}}">
                <i>9</i>
                <span>Lease Escalation</span>
            </a>
        </li>

        <li @if(10 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'select-low-value') active @endif"
               href="{{route('addlease.lowvalue.index',['id'=>$lease->id])}}">
                <i>10</i>
                <span>Select Low Value</span>
            </a>
        </li>

        <li @if(11 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'fair-market-value') active @endif"
               href="{{route('addlease.fairmarketvalue.index',['id'=>$lease->id])}}">
                <i>11</i>
                <span>Fair Market Value</span>
            </a>
        </li>

        <li @if(12 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'select-discount-rate') active @endif"
               href="{{route('addlease.discountrate.index',['id'=>$lease->id])}}">
                <i>12</i>
                <span>Select Discount Rate</span>
            </a>
        </li>
        <li @if(13 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'lease-balnce-as-on-dec') active @endif"
               href="{{route('addlease.balanceasondec.index',['id'=>$lease->id])}}">
                <i>13</i>
                <span>Lease Balances</span>
            </a>
        </li>
        <li @if(14 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'initial-direct-cost') active @endif"
               href="{{route('addlease.initialdirectcost.index',['id'=>$lease->id])}}">
                <i>14</i>
                <span>Initial Direct Cost</span>
            </a>
        </li>
        <li @if(15 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'lease-incentives') active @endif"
               href="{{route('addlease.leaseincentives.index',['id'=>$lease->id])}}">
                <i>15</i>
                <span>Lease Incentives</span>
            </a>
        </li>
        <li @if(16 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'lease-valuation') active @endif"
               href="{{route('addlease.leasevaluation.index',['id'=>$lease->id])}}">
                <i>16</i>
                <span>Lease Valuation</span>
            </a>
        </li>
        <li @if(17 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'lease-payment-invoice') active @endif"
               href="{{route('addlease.leasepaymentinvoice.index',['id'=>$lease->id])}}">
                <i>17</i>
                <span>Lessor Invoice</span>
            </a>
        </li>
        <li @if(18 <= $current_step) class="active" @else class="disabled" @endif>
            <a class="@if(request()->segment(2) == 'review-submit') active @endif"
               href="{{route('addlease.reviewsubmit.index',['id'=>$lease->id])}}">
                <i>18</i>
                <span>Review & Submit</span>
            </a>
        </li>

    </ul>
</div>