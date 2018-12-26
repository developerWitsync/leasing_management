<a href="/home" class="list-group-item @if(request()->segment('1') == 'home') active @endif"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>

@if(Auth::user()->can('add_lease'))
    <a href="{{ route('add-new-lease.index') }}" class="list-group-item @if(request()->segment('1') == 'add-new-lease') active @endif"><i class="fa fa-plus-square"></i> <span>Add New Lease</span></a>
@endif

<a href="#" class="list-group-item"><i class="fa fa-envelope-square"></i> <span>Drafts Saved</span></a>
<a href="#" class="list-group-item"><i class="fa fa-pencil-square"></i> <span>Modify Lease</span></a>
<a href="#" class="list-group-item"><i class="fa fa-dollar"></i> <span>Lease Valuation</span></a>
<a href="#" class="list-group-item"><i class="fa fa-calendar-minus-o"></i> <span>Active/Expired Leases</span></a>
<a href="#" class="list-group-item"><i class="fa fa-drivers-license-o"></i> <span>Leasing Disclosure</span></a>
<a href="#" class="list-group-item"><i class="fa fa-cart-arrow-down"></i> <span>Lease Asset Inventory</span></a>

@if(Auth::user()->can('settings'))
    <a href="{{ route('settings.index') }}" class="list-group-item @if(request()->segment('1') == 'settings') active @endif">
        <i class="fa fa-cogs"></i>
        <span>Settings</span>
    </a>
@endif

<a href="#" class="list-group-item"><i class="fa fa-balance-scale"></i> <span>Resource Material</span></a>