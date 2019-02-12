
{{--<div class="settNavOuter">--}}
    <nav class="navbar navbar-inverse settNavOuter" data-spy="affix" data-offset-top="197">
        <ul class="nav nav-tabs">
            <li role="presentation" class="@if(!request()->has('id'))  active @endif"><a href="{{ route('leasevaluation.index', ['capitalized' => $capitalized]) }}">Consolidated</a></li>
             @foreach($categories as $key=>$value)
            <li role="presentation" class="@if(request()->has('id') && request()->id == $value->id) class="active" @endif"><a href="{{ route('leasevaluation.index',['capitalized' => $capitalized,'id' => $value->id ])}}">{{ $value->title }}</a>
            </li>
            @endforeach
        </ul>
    </nav>
{{--</div>--}}