@if(auth()->check())
    <div class="ifrsBx" style="display:inline-block;padding:10px; ">
        <span class="badge badge-primary"
              style="padding: 10px; line-height:20px; font-size:14px; border-radius: 30px;">{{ getParentDetails()->accountingStandard->title }}</span>
    </div>
@endif
<ul class="mainMenu">

    @if(Auth::user()->can('dashboard'))
        <li>
            <a href="/home" class="list-group-item @if(request()->segment('1') == 'home') active @endif">
                <img width="28" src="{{ asset('images/icons/dashboard.png') }}"> <span
                        style="vertical-align: sub">Dashboard</span>
                <!--  <small class="fa fa-angle-right" aria-hidden="true"></small> -->
            </a>
        </li>
    @endif

    @if(Auth::user()->can('add_lease'))
        <li>
            <a href="{{ route('add-new-lease.index') }}"
               class="list-group-item @if(request()->segment('1') == 'lease') active @endif">
                <img width="28" src="{{ asset('images/icons/plus.png') }}">
                <span style="vertical-align: sub">Add New Lease</span>
                <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
            </a>
        </li>
    @endif

    @if(Auth::user()->can('drafts'))
        <li>
            <a href="{{route('drafts.index')}}"
               class="list-group-item @if(request()->segment('1') == 'drafts') active @endif">
                <img width="28" src="{{ asset('images/icons/draft.png') }}"> <span
                        style="vertical-align: sub">Drafts Saved</span>
            </a>
        </li>
    @endif

    @if(Auth::user()->can('modify_lease'))
        <li>
            <a href="{{route('modifylease.index')}}"
               class="list-group-item  @if(request()->segment('1') == 'modify-lease') active @endif">
                <img width="28" src="{{ asset('images/icons/edit.png') }}"> <span
                        style="vertical-align: sub">Modify Lease</span>
            </a>
        </li>
    @endif

    <li>
        <a href="#" class="list-group-item"><img width="28" src="{{ asset('images/icons/fair_valuation.png') }}"> <span>Fair Valuation</span></a>
    </li>

    <li>
        <a href="#" class="list-group-item"><img width="28" src="{{ asset('images/icons/testing.png') }}"> <span>Impairment Testing</span></a>
    </li>

    <li class="mainMenuItem">
        <a href="javascript:void(0);" class="list-group-item"><img width="28"
                                                                   src="{{ asset('images/icons/cash.png') }}">
            <span>Lease Cash Flows</span>
            <i class="fa fa-chevron-down" style="float: right"></i>
        </a>
        <ul class="subMenuItem">
            <li class="menu-item"><a tabindex="-1" href="#">Leases Due</a></li>
            <li class="menu-item"><a tabindex="-1" href="#">Cash Flow Planning</a></li>
        </ul>
    </li>

    {{--<li class="mainMenuItem">--}}
        {{--<a href="javascript:void(0);"--}}
           {{--class="list-group-item @if(request()->segment('1') == 'lease-valuation' && request()->capitalized == 1) active @endif"><img--}}
                    {{--width="28"--}}
                    {{--src="{{ asset('images/icons/dollar.png') }}">--}}
            {{--<span>Valuation - CAP</span>--}}
            {{--<i class="fa fa-chevron-down" style="float: right"></i>--}}
        {{--</a>--}}
        {{--<ul class="subMenuItem">--}}
            {{--<li class="menu-item"><a--}}
                        {{--class="@if(request()->segment('1') == 'lease-valuation' && request()->capitalized == 1) active @endif"--}}
                        {{--tabindex="-1" href="{{route('leasevaluation.index', ['capitalized' => 1])}}">Initial /--}}
                    {{--Subsequent</a></li>--}}
            {{--<li class="menu-item"><a tabindex="-1" href="#">Interest Expense</a></li>--}}
            {{--<li class="menu-item"><a tabindex="-1" href="#">Depreciation</a></li>--}}
        {{--</ul>--}}
    {{--</li>--}}

    <li>
        <a href="{{ route('leasevaluation.cap.index') }}" class="@if(request()->segment('2') == 'valuation-capitalised') active @endif list-group-item"><img width="28" src="{{ asset('images/icons/dollar.png') }}"> <span>Valuation CAP</span></a>
    </li>

    <li>
        <a href="{{route('leasevaluation.index', ['capitalized' => 0])}}"
           class="@if(request()->segment('1') == 'lease-valuation' && request()->has('capitalized') && request()->capitalized == 0) active @endif list-group-item"><img
                    width="28" src="{{ asset('images/icons/ncap.png') }}">
            <span>Valuation - NCAP</span></a>
    </li>

    <li>
        <a href="#" class="list-group-item"><img width="28" src="{{ asset('images/icons/presentation.png') }}"> <span>Presentation</span>
            <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
        </a>
    </li>

    <li>
        <a href="#" class="list-group-item"><img width="28" src="{{ asset('images/icons/disclosure.png') }}">
            <span>Disclosure</span>
            <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
        </a>
    </li>

    <li>
        <a href="#" class="list-group-item"><img width="28" src="{{ asset('images/icons/documents.png') }}">
            <span>Documents</span>
            <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
        </a>
    </li>

    <li>
        <a href="#" class="list-group-item"><img width="28" src="{{ asset('images/icons/expired.png') }}">
            <span>Expired Leases</span>
            <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
        </a>
    </li>

    @if(Auth::user()->can('settings'))
        <li>
            <a href="{{ route('settings.index') }}"
               class="list-group-item @if(request()->segment('1') == 'settings') active @endif">
                <img width="28" src="{{ asset('images/icons/settings.png') }}">
                <span>Settings</span>
                <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
            </a>
        </li>
    @endif

    <li>
        <a href="#" class="list-group-item"><img width="28" src="{{ asset('images/icons/books.png') }}">
            <span>Resource Material</span>
        </a>
    </li>

    @if(Auth::user()->can('manage_subscription'))
        <li>
            <a href="{{ route('plan.index') }}"
               class="list-group-item @if(request()->segment('1') == 'plan') active @endif">
                <img width="28"
                     src="{{ asset('images/icons/renew-subscription.png') }}"><span>Manage Subscription</span>
                <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
            </a>
        </li>
    @endif

    <li>
        <a href="{{ route('logout') }}"
           onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
           class="list-group-item"><img width="28" src="{{ asset('images/icons/logout.png') }}"> <span>Logout</span>
            <!-- <small class="fa fa-angle-right" aria-hidden="true"></small> -->
        </a>
    </li>

</ul>