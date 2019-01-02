<ul class="nav nav-tabs">
    <li role="presentation" class="@if(request()->segment(2) == 'lessor-details') active @endif"><a href="{{ route('add-new-lease.index') }}">Lessor Details</a></li>
    <li role="presentation" class="@if(request()->segment(2) == 'underlying-lease-assets') active @endif"><a href="">Underlying Lease Asset</a></li>
    <li role="presentation" class="@if(request()->segment(2) == 'payments') active @endif"><a href="">Add Lease Payments</a></li>
    <li role="presentation" class="@if(request()->segment(2) == 'fair-market-value') active @endif"><a href="">Fair Market Value</a></li>
    <li role="presentation" class="@if(request()->segment(2) == 'residual-value-gurantee') active @endif"><a href="">Residual Value Gurantee</a></li>
    <li role="presentation" class=""><a href="">Termination Option</a></li>
    <li role="presentation" class=""><a href="">Renewal Option</a></li>
    <li role="presentation" class=""><a href="">Purchase Option</a></li>
    <li role="presentation" class=""><a href="">Lease Duration Classified</a></li>
    <li role="presentation" class=""><a href="">Lease Esclation</a></li>
    <li role="presentation" class=""><a href="">Select Low Value</a></li>
    <li role="presentation" class=""><a href="">Select Discount Rate </a></li>
    <li role="presentation" class=""><a href="">Lease Balances as on Dec 31,2018 </a></li>
    <li role="presentation" class=""><a href="">Initial Direct Cost </a></li>
    <li role="presentation" class=""><a href="">Lease Incentives </a></li>
    <li role="presentation" class=""><a href="">Lease Valuation </a></li>
    <li role="presentation" class=""><a href="">Review & Submit </a></li> 
</ul>