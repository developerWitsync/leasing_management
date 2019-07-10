<div class="tabsOuter clearfix">
    <div class="Privacy_left">
        <ul>
            @foreach(getInformationPage() as $key=>$page)
                <li><a href="#{{$page->slug}}" @if($key == 0) class="active" @endif>{{ $page->title }}</a></li>
            @endforeach
        </ul>
    </div>
    <div class="privacyRight">
        @foreach(getInformationPage() as $page)
            <div class="PrivacyTab privacyContent" id="{{$page->slug}}" style="display:block;">
                {!! $page->description !!}
            </div>
        @endforeach
    </div>
</div>