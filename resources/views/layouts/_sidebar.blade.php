@if(auth()->check())
    <div class="ifrsBx" style="display:inline-block;padding:10px; ">
        <span class="badge badge-primary" style="padding: 10px; line-height:20px; font-size:14px; border-radius: 30px;">{{ getParentDetails()->applicable_gaap }}</span>
    </div>
@endif

<a href="/home" class="list-group-item @if(request()->segment('1') == 'home') active @endif">
    <i class="fa fa-home"></i> <span>Dashboard</span>
   <!--  <small class="fa fa-angle-right" aria-hidden="true"></small> -->
</a>
@if(Auth::user()->can('add_lease'))
<a href="{{ route('add-new-lease.index') }}" class="list-group-item @if(request()->segment('1') == 'lease') active @endif">
    <i class="fa fa-plus-square"></i>
    <span>Add New Lease</span>
    <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
</a>
@endif
@if(Auth::user()->can('drafts')) 
<a href="{{route('drafts.index')}}" class="list-group-item @if(request()->segment('1') == 'drafts') active @endif">
    <i class="fa fa-envelope-square"></i> 
    <span>Drafts Saved</span>
</a>
@endif
@if(Auth::user()->can('modify_lease')) 
    <a href="{{route('modifylease.index')}}" class="list-group-item  @if(request()->segment('1') == 'modify-lease') active @endif">
        <i class="fa fa-sticky-note"></i>
        <span>Modify Lease</span>
    </a>
@endif


<a href="{{route('leasevaluation.index')}}" class="list-group-item  @if(request()->segment('1') == 'lease-valuation') active @endif"><i class="fa fa-dollar"></i> <span>Lease Valuation</span></a>
<a href="#" class="list-group-item"><i class="fa fa-calendar-minus-o"></i> <span>Active/Expired Leases</span>
    <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
</a>
<a href="#" class="list-group-item"><i class="fa fa-drivers-license-o"></i> <span>Leasing Disclosure</span>
    <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
</a>

<a href="#" class="list-group-item"><i class="fa fa-cart-arrow-down"></i> <span>Lease Asset Inventory</span>
    <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
</a>

<a href="#" class="list-group-item"><i class="fa fa-file-powerpoint-o"></i> <span>Presentation</span>
    <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
</a>

<a href="#" class="list-group-item"><i class="fa fa-file-text-o"></i> <span>Disclosures</span>
    <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
</a>
<a href="#" class="list-group-item"><i class="fa fa-book"></i> <span>Documents</span>
    <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
</a>

<a href="#" class="list-group-item"><i class="fa fa-calendar-times-o"></i> <span>Expired Leases</span>
    <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
</a>

@if(Auth::user()->can('settings'))
    <a href="{{ route('settings.index') }}" class="list-group-item @if(request()->segment('1') == 'settings') active @endif">
        <i class="fa fa-cogs"></i>
        <span>Settings</span>
        <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
    </a>
@endif

<a href="#" class="list-group-item"><i class="fa fa-balance-scale"></i> <span>Resource Material</span>

<a href="{{ route('plan.index') }}" class="list-group-item @if(request()->segment('1') == 'plan') active @endif"><i class="fa fa-arrow-circle-o-up"></i> <span>Manage Subscription</span>
<!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
</a>
<a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="list-group-item"><i class="fa fa-sign-out"></i> <span>Logout</span>
<!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
</a>


