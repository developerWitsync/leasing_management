<div class="">
    <ul class="nav nav-pills">
        <li role="presentation" class="@if(!request()->has('id'))  active @endif"><a
                    href="{{ route('leasevaluation.index', ['capitalized' => $capitalized]) }}">Consolidated</a></li>
        @foreach($categories as $key=>$value)
            <li role="presentation" class="@if(request()->has('id') && request()->id == $value->id) active @endif"><a
                        href="{{ route('leasevaluation.index',['capitalized' => $capitalized,'id' => $value->id ])}}">{{$value['title']}}</a></button>
            </li>
        @endforeach
    </ul>
</div>

