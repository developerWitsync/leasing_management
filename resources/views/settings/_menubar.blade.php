<ul class="nav nav-tabs">
    <li role="presentation" class="@if(request()->segment(2) == 'general') active @endif"><a href="{{ route('settings.index') }}">General Settings</a></li>
    <li role="presentation" class="@if(request()->segment(2) == 'lease-classification') active @endif"><a href="{{ route('settings.leaseclassification') }}">Lease Classification</a></li>
    <li role="presentation" class="@if(request()->segment(2) == 'currencies') active @endif"><a href="{{ route('settings.currencies') }}">Currencies</a></li>
    <li role="presentation" class="@if(request()->segment(2) == 'lease-assets') active @endif"><a href="{{ route('settings.leaseassets') }}">Lease Assets</a></li>
    <li role="presentation" class="@if(request()->segment(2) == 'user-access') active @endif"><a href="{{ route('settings.useraccess') }}">User Access</a></li>
    <li role="presentation" class="@if(request()->segment(2) == 'codification') active @endif"><a href="{{ route('settings.codification') }}">Codification</a></li>
    <li role="presentation" class="@if(request()->segment(2) == 'companyprofile') active @endif"><a href="{{ route('settings.companyprofile.index', ['id' => auth()->user()->id])}}">My Profile</a></li>

</ul>