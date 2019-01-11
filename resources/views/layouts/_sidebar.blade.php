<a href="/home" class="list-group-item @if(request()->segment('1') == 'home') active @endif"><i class="fa fa-dashboard"></i> <span>Dashboard</span>
<small class="fa fa-angle-right" aria-hidden="true"></small>
</a>

@if(Auth::user()->can('add_lease'))
    <a href="{{ route('add-new-lease.index') }}" class="list-group-item @if(request()->segment('1') == 'lease') active @endif">
        <i class="fa fa-plus-square"></i>
        <span>Add New Lease</span>
        <small class="fa fa-angle-right" aria-hidden="true"></small>
    </a>
@endif
@if(Auth::user()->can('drafts')) 
<a href="{{route('drafts.index')}}" class="list-group-item @if(request()->segment('1') == 'drafts') active @endif"><i class="fa fa-envelope-square"></i> <span>Drafts Saved</span></a>
@endif
@if(Auth::user()->can('modify_lease')) 
<a href="{{route('modifylease.index')}}" class="list-group-item  @if(request()->segment('1') == 'modify-lease') active @endif"><i class="fa fa-pencil-square"></i> <span>Modify Lease</span></a>
@endif


<a href="#" class="list-group-item"><i class="fa fa-dollar"></i> <span>Lease Valuation</span></a>
<a href="#" class="list-group-item"><i class="fa fa-calendar-minus-o"></i> <span>Active/Expired Leases</span><small class="fa fa-angle-right" aria-hidden="true"></small></a>
<a href="#" class="list-group-item"><i class="fa fa-drivers-license-o"></i> <span>Leasing Disclosure</span><small class="fa fa-angle-right" aria-hidden="true"></small></a>
<a href="#" class="list-group-item"><i class="fa fa-cart-arrow-down"></i> <span>Lease Asset Inventory</span><small class="fa fa-angle-right" aria-hidden="true"></small></a>
@if(Auth::user()->can('settings'))
    <a href="{{ route('settings.index') }}" class="list-group-item @if(request()->segment('1') == 'settings') active @endif">
        <i class="fa fa-cogs"></i>
        <span>Settings</span>
        <small class="fa fa-angle-right" aria-hidden="true"></small>
    </a>
@endif

<a href="#" class="list-group-item"><i class="fa fa-balance-scale"></i> <span>Resource Material</span>
<small class="fa fa-angle-right" aria-hidden="true"></small>
</a>
